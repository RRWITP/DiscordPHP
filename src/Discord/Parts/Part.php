<?php

namespace Discord\Parts;

use ArrayAccess;
use Carbon\Carbon;
use Discord\Discord;
use Discord\Factory\Factory;
use Discord\Http\Http;
use Discord\Wrapper\CacheWrapper;
use Illuminate\Support\Str;
use JsonSerializable;
use React\Promise\Promise;
use Serializable;

/**
 * This class is the base of all objects that are returned. All "Parts" extend off this
 * base class.
 */
abstract class Part implements ArrayAccess, Serializable, JsonSerializable
{
    /**
     * THe HTTP client.
     *
     * @var Http Client.
     */
    protected $http;

    /**
     * The factory.
     *
     * @var Factory Factory.
     */
    protected $factory;

    /**
     * The cache wrapper.
     *
     * @var CacheWrapper Cache.
     */
    protected $cache;

    /**
     * The Discord client.
     *
     * @var Discord Client.
     */
    protected $discord;

    /**
     * The parts fillable attributes.
     *
     * @var array The array of attributes that can be mass-assigned.
     */
    protected $fillable = [];

    /**
     * The parts attributes.
     *
     * @var array The parts attributes and content.
     */
    protected $attributes = [];

    /**
     * The parts attributes cache.
     *
     * @var array Attributes which are cached such as parts that are retrieved over REST.
     */
    protected $attributes_cache = [];

    /**
     * Attributes that are hidden from debug info.
     *
     * @var array Attributes that are hidden from public.
     */
    protected $hidden = [];

    /**
     * An array of repositories that can exist in a part.
     *
     * @var array Repositories.
     */
    protected $repositories = [];

    /**
     * Is the part already created in the Discord servers?
     *
     * @var bool Whether the part has been created.
     */
    public $created = false;

    /**
     * The regex pattern to replace variables with.
     *
     * @var string The regex which is used to replace placeholders.
     */
    protected $regex = '/:([a-z_]+)/';

    /**
     * Should we fill the part after saving?
     *
     * @var bool Whether the part will be saved after being filled.
     */
    protected $fillAfterSave = true;

    /**
     * The promise resolve function.
     *
     * @var \Closure Resolve function.
     */
    public $resolve;

    /**
     * The promise reject function.
     *
     * @var \Closure Reject function.
     */
    public $reject;

    /**
     * Create a new part instance.
     *
     * @param Factory      $factory    The factory.
     * @param Discord      $discord    The Discord client.
     * @param Http         $http       The HTTP client.
     * @param CacheWrapper $cache      The cache.
     * @param array        $attributes An array of attributes to build the part.
     * @param bool         $created    Whether the part has already been created.
     *
     * @return void
     */
    public function __construct(Factory $factory, Discord $discord, Http $http, CacheWrapper $cache, array $attributes = [], bool  $created = false)
    {
        $this->factory = $factory;
        $this->discord = $discord;
        $this->http    = $http;
        $this->cache   = $cache;

        $this->created = $created;
        $this->fill($attributes);

        if (is_callable([$this, 'afterConstruct'])) {
            $this->afterConstruct();
        }

        $this->resolve = static function ($response, $deferred): void {
            if (is_null($response)) {
                $response = true;
            }

            $deferred->resolve(true);
        };

        $this->reject = static function ($e, $deferred) {
            $deferred->reject($e);
        };
    }

    /**
     * Fills the parts attributes from an array.
     *
     * @param  array $attributes An array of attributes to build the part.
     * @return void
     */
    public function fill($attributes): void
    {
        foreach ($attributes as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->setAttribute($key, $value);
            }
        }
    }

    /**
     * Clears the attribute cache.
     *
     * Returns whether the attempt to clear the cache succeeded or failed.
     *
     * @return bool
     */
    public function clearCache(): bool
    {
        $this->attributes_cache = [];

        return true;
    }

    /**
     * Checks if there is a mutator present.
     *
     * @param  string $key  The attribute name to check.
     * @param  string $type Either get or set.
     * @return mixed
     */
    public function checkForMutator(string $key, string $type)
    {
        $str = $type.Str::studly($key).'Attribute';

        if (is_callable([$this, $str])) {
            return $str;
        }

        return false;
    }

    /**
     * Replaces variables in string with syntax :{varname}.
     *
     * @param  string $string A string with placeholders.
     * @return string
     */
    public function replaceWithVariables(string $string): string
    {
        $matches = null;
        $matcher = preg_match_all($this->regex, $string, $matches);

        $original = $matches[0];
        $vars     = $matches[1];

        foreach ($vars as $key => $variable) {
            if ($attribute = $this->getAttribute($variable)) {
                $string = str_replace($original[$key], $attribute, $string);
            }
        }

        return $string;
    }

    /**
     * Replaces variables in one of the URIs.
     *
     * @param  string $key    A key from URIs.
     * @param  array  $params Parameters to replace placeholders with.
     * @return string
     *
     * @see self::$uris The URIs that can be replaced.
     */
    public function uriReplace(string $key, array $params): string
    {
        $string = $this->uris[$key];

        $matches = null;
        $matcher = preg_match_all($this->regex, $string, $matches);

        $original = $matches[0];
        $vars     = $matches[1];

        foreach ($vars as $key => $variable) {
            if ($attribute = $params[$variable]) {
                $string = str_replace($original[$key], $attribute, $string);
            }
        }

        return $string;
    }

    /**
     * Gets an attribute on the part.
     *
     * @param  string $key The key to the attribute.
     * @return mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getAttribute(string $key)
    {
        if (isset($this->repositories[$key])) {
            $className = str_replace('\\', '', $this->repositories[$key]);

            if ($repository = $this->cache->get("repositories.{$className}.{$this->id}.{$key}")) {
                return $repository;
            }

            $class = $this->repositories[$key];

            return $this->cache->set(
                "repositories.{$className}.{$this->id}.{$key}",
                new $class(
                    $this->http,
                    $this->cache,
                    $this->factory,
                    $this->getRepositoryAttributes()
                )
            );
        }

        if ($str = $this->checkForMutator($key, 'get')) {
            $result = $this->{$str}();

            return $result;
        }

        if (! isset($this->attributes[$key])) {
            return;
        }

        return $this->attributes[$key];
    }

    /**
     * Sets an attribute on the part.
     *
     * @param  string $key   The key to the attribute.
     * @param  mixed  $value The value of the attribute.
     * @return void
     */
    public function setAttribute(string $key, $value): void
    {
        if (isset($this->repositories[$key])) {
            if (! ($value instanceof $this->repositories[$key])) {
                return;
            }

            $className = str_replace('\\', '', $this->repositories[$key]);
            $this->cache->set("repositories.{$className}.{$this->id}.{$key}", $value);

            return;
        }

        if ($str = $this->checkForMutator($key, 'set')) {
            $this->{$str}($value);

            return;
        }

        if (array_search($key, $this->fillable) !== false) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Sets a cache attribute on the part.
     *
     * @param  string $key   The cache key.
     * @param  mixed  $value The cache value.
     * @return void
     */
    public function setCache($key, $value)
    {
        $this->attributes_cache[$key] = $value;
    }

    /**
     * Checks if the cache has a specific key.
     *
     * @param  string $key The key to check for.
     * @return bool
     */
    public function cacheHas($key): bool
    {
        return isset($this->attributes_cache[$key]);
    }

    /**
     * Gets an attribute via key. Used for ArrayAccess.
     *
     * @param  string $key The attribute key.
     * @return mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @see self::getAttribute() This function forwards onto getAttribute.
     */
    public function offsetGet($key): bool
    {
        return $this->getAttribute($key);
    }

    /**
     * Checks if an attribute exists via key. Used for ArrayAccess.
     *
     * @param  string $key The attribute key.
     * @return bool
     */
    public function offsetExists($key): bool
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Sets an attribute via key. Used for ArrayAccess.
     *
     * @param  string $key   The attribute key.
     * @param  mixed  $value The attribute value.
     * @return void
     *
     * @see self::setAttribute() This function forwards onto setAttribute.
     */
    public function offsetSet($key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Unsets an attribute via key. Used for ArrayAccess.
     *
     * @param  string $key The attribute key.
     * @return void
     */
    public function offsetUnset($key): void
    {
        if (isset($this->attributes[$key])) {
            unset($this->attributes[$key]);
        }
    }

    /**
     * Serializes the data. Used for Serializable.
     *
     * @return mixed A string of serialized data.
     */
    public function serialize()
    {
        return serialize($this->attributes);
    }

    /**
     * Unserializes some data and stores it. Used for Serializable.
     *
     * @param  mixed $data Some serialized data.
     * @return mixed Unserialized data.
     *
     * @see self::setAttribute() The unserialized data is stored with setAttribute.
     */
    public function unserialize($data)
    {
        $data = unserialize($data);

        foreach ($data as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Provides data when the part is encoded into JSON. Used for JsonSerializable.
     *
     * @return array
     *
     * @see self::getPublicAttributes() This function forwards onto getPublicAttributes.
     */
    public function jsonSerialize(): array
    {
        return $this->getPublicAttributes();
    }

    /**
     * Returns an array of public attributes.
     *
     * @return array
     */
    public function getPublicAttributes(): array
    {
        $data = [];

        foreach ($this->fillable as $key) {
            if (in_array($key, $this->hidden)) {
                continue;
            }

            $value = $this->getAttribute($key);

            if ($value instanceof Carbon) {
                $value = $value->format('Y-m-d\TH:i:s\Z');
            }

            $data[$key] = $value;
        }

        return $data;
    }

    /**
     * Returns an array of raw attributes.
     *
     * @return array
     */
    public function getRawAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Gets the attributes to pass to repositories.
     *
     * @return array
     */
    public function getRepositoryAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Converts the part to a string.
     *
     * @return string
     *
     * @see self::getPublicAttributes() This function encodes getPublicAttributes into JSON.
     */
    public function __toString(): string
    {
        return json_encode($this->getPublicAttributes());
    }

    /**
     * Handles debug calls from var_dump and similar functions.
     *
     * @return array
     *
     * @see self::getPublicAttributes() This function forwards onto getPublicAttributes.
     */
    public function __debugInfo(): array
    {
        return $this->getPublicAttributes();
    }

    /**
     * Handles dynamic get calls onto the part.
     *
     * @param  string $key The attributes key.
     * @return mixed
     *
     * @see self::getAttribute() This function forwards onto getAttribute.
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Handles dynamic set calls onto the part.
     *
     * @param  string $key   The attributes key.
     * @param  mixed  $value The attributes value.
     * @return void
     *
     * @see self::setAttribute() This function forwards onto setAttribute.
     */
    public function __set($key, $value): void
    {
        $this->setAttribute($key, $value);
    }
}
