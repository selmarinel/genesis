<?php

namespace App\Services\Caching;

use Closure;
use Symfony\Component\Cache\Simple\FilesystemCache;

class Handler
{
    /** @var FilesystemCache */
    private $cache;

    public function __construct()
    {
        $this->cache = new FilesystemCache();
    }

    /**
     * @param string $key
     * @param Closure $closure
     * @return mixed|null
     */
    public function getFromCacheByKey(string $key, Closure $closure)
    {
        if ($this->cache->has("$key.result")) {
            return $this->cache->get("$key.result");
        }
        if (!$this->cache->has("$key.lock")) {
            $this->cache->set("$key.lock", 1, 30);
            $result = $closure();
            $this->cache->set("$key.result", $result, 30);
            return $result;
        }
        sleep(5);
        return $this->getFromCacheByKey($key, $closure);
    }

    /**
     * @param string $key
     * @param string|null $filter
     * @param Closure $closure
     * @return mixed|null
     */
    public function getFromCacheByFilters(string $key, string $filter = null, Closure $closure)
    {
        $cachedFilter = $this->cache->get("$key.filters");
        $isSeems = $filter === $cachedFilter;

        if ($this->cache->has("$key.filters") && !$isSeems) {
            $this->cache->delete("$key.result");
        }

        if ($this->cache->has("$key.result") && $isSeems) {
            return $this->cache->get("$key.result");
        }
        if (!$this->cache->has("$key.lock")) {
            $this->cache->set("$key.lock", 1, 10);
            $this->cache->set("$key.filters", $filter, 10);
            $result = $closure();
            $this->cache->set("$key.result", $result, 10);
            return $result;
        }
        sleep(5);
        return $this->getFromCacheByFilters($key, $filter, $closure);
    }

    /**
     * @param $key
     */
    public function removeKeyFromCache(string $key): void
    {
        $this->cache->delete("$key.result");
    }
}