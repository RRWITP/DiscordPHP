<?php

namespace Discord\Wrapper;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Provides an easy wrapper for the Cache Adapter.
 *
 * @author Aaron Scherer <aequasi@gmail.com>
 * @package Discord\Wrapper
 */
class CacheWrapper
{
    private CacheItemPoolInterface $cache;

    /**
     * CacheWrapper constructor.
     *
     * @param  CacheItemPoolInterface $cache
     * @return void
     */
    public function __construct(CacheItemPoolInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Determine whether the cache has the given key or not.
     *
     * @param  string $key The key that u want to check in the cache.
     * @return bool
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function has(string $key): bool
    {
        return $this->cache->hasItem($key);
    }

    /**
     * Get an item out of the cache.
     *
     * @param  string $key The key u want to get out of the cache.
     * @return mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function get(string $key)
    {
        return $this->cache->getItem($key)->get();
    }

    /**
     * Register an item in the cache.
     *
     * @param  string $key      The cache key where u want to store your data under.
     * @param  mixed  $value    The value u want to store in the cache.
     * @param  int    $ttl      The time to live in the cache (seconds)
     * @return mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function set(string $key, $value, ?int $ttl = null)
    {
        $item = $this->cache->getItem($key);
        $item->set($value);
        $item->expiresAfter($ttl);

        $this->cache->save($item);

        return $item->get();
    }

    /**
     * Remove an item key from the cache.
     *
     * @param  string $key  The key value u want to delete in the cache.
     * @return void
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function remove(string $key): void
    {
        $this->cache->deleteItem($key);
    }

    /**
     * Handles dynamic calls to the class.
     *
     * @param  string $function The function called.
     * @param  array  $params   Parameters.
     * @return mixed
     */
    public function __call(string $function, array $params)
    {
        return call_user_func_array([$this->cache, $function], $params);
    }
}
