<?php

namespace App\Services;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;

/**
 * Centralizes product query caching.
 *
 * Features covered:
 *  - Cache Tags (group invalidation) when the store supports it
 *  - Configurable TTL via config/querycache.php (CACHE_TTL)
 *  - Cache versioning / namespace (global invalidation on data change)
 *  - remember() helper wrapping manual has/get/put
 */
class ProductCacheService
{
    public const TAG = 'products';

    /** Live version stored in the cache so it survives restarts. */
    public static function version(): int
    {
        return (int) Cache::get('product_cache_version', config('querycache.version', 1));
    }

    public static function bumpVersion(): void
    {
        Cache::forever('product_cache_version', self::version() + 1);

        if (self::isTaggable()) {
            Cache::tags(self::TAG)->flush();
        }
    }

    public static function getTtl(): int
    {
        return (int) config('querycache.ttl', 10);
    }

    public static function isTaggable(): bool
    {
        return Cache::getStore() instanceof TaggableStore;
    }

    /** Builds a versioned cache key (namespace). */
    public static function key(string $base): string
    {
        return 'v' . self::version() . '_' . $base;
    }

    public static function has(string $base): bool
    {
        $key = self::key($base);

        return self::isTaggable()
            ? Cache::tags(self::TAG)->has($key)
            : Cache::has($key);
    }

    public static function get(string $base)
    {
        $key = self::key($base);

        return self::isTaggable()
            ? Cache::tags(self::TAG)->get($key)
            : Cache::get($key);
    }

    /** remember() helper — tags + versioning + configurable TTL. */
    public static function remember(string $base, callable $callback)
    {
        $key = self::key($base);
        $ttl = self::getTtl();

        return self::isTaggable()
            ? Cache::tags(self::TAG)->remember($key, $ttl, $callback)
            : Cache::remember($key, $ttl, $callback);
    }
}
