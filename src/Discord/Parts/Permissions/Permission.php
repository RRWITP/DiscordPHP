<?php

namespace Discord\Parts\Permissions;

use Discord\Discord;
use Discord\Factory\Factory;
use Discord\Http\Http;
use Discord\Parts\Part;
use Discord\Wrapper\CacheWrapper;

/**
 * The permissions object of a role or channel.
 */
class Permission extends Part
{
    /**
     * {@inheritdoc}
     */
    public function __construct(Factory $factory, Discord $discord, Http $http, CacheWrapper $cache, array $attributes = [], bool $created = false)
    {
        $this->fillable   = array_keys($this->bitwise);
        $this->fillable[] = 'bitwise';

        $default = [];

        foreach ($this->bitwise as $key => $bit) {
            $default[$key] = false;
        }

        $default = array_merge($default, $this->getDefault());

        parent::__construct($factory, $discord, $http, $cache, $default, $created);
        $this->fill($attributes);
    }

    /**
     * Decodes a bitwise integer.
     *
     * @param  int $bitwise The bitwise integer to decode.
     * @return self
     */
    public function decodeBitwise($bitwise): self
    {
        $result = [];

        foreach ($this->bitwise as $key => $value) {
            $result[$key] = ((($bitwise >> $value) & 1) == 1);
        }

        $this->fill($result);

        return $this;
    }

    /**
     * Retrieves the bitwise integer.
     *
     * @return array|int
     */
    public function getBitwiseAttribute()
    {
        $bitwise = 0;

        foreach ($this->attributes as $key => $value) {
            if ($value) {
                $bitwise |= (1 << $this->bitwise[$key]);
            } else {
                $bitwise &= ~(1 << $this->bitwise[$key]);
            }
        }

        return $bitwise;
    }

    /**
     * Returns the default permissions.
     *
     * @return array
     */
    public function getDefault(): array
    {
        return [];
    }
}
