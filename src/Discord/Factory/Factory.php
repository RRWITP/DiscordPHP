<?php

namespace Discord\Factory;

use Discord\Discord;
use Discord\Http\Http;
use Discord\Wrapper\CacheWrapper;

/**
 * Exposes an interface to build part objects without the other requirements.
 */
class Factory
{
    /**
     * The Discord client.
     */
    protected Discord $discord;

    /**
     * The HTTP client.
     */
    protected Http $http;

    /**
     * The cacheWrapper for the driver.
     */
    protected CacheWrapper $cache;
    /**
     * Constructs a factory.
     *
     * @param Discord      $discord The Discord client.
     * @param Http         $http    The HTTP client.
     * @param CacheWrapper $cache   The cache.
     */
    public function __construct(Discord $discord, Http $http, CacheWrapper $cache)
    {
        $this->discord = $discord;
        $this->http    = $http;
        $this->cache   = $cache;
    }

    /**
     * Creates an object.
     *
     * @param  string $class The class to build.
     * @param  array $data Data to create the object.
     * @param  bool $created Whether the object is created (if part).
     * @return mixed The object.
     *
     * @throws \Exception
     */
    public function create($class, $data = [], $created = false)
    {
        if (! is_array($data)) {
            $data = (array) $data;
        }

        if (strpos($class, 'Discord\\Parts') !== false) {
            $object = new $class($this, $this->discord, $this->http, $this->cache, $data, $created);
        } elseif (strpos($class, 'Discord\\Repository') !== false) {
            $object = new $class($this->http, $this->cache, $this, $data);
        } else {
            throw new \Exception('The class '.$class.' is not a Part or a Repository.');
        }

        return $object;
    }
}
