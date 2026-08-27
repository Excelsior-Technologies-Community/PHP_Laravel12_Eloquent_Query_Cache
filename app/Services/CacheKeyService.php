<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Inspects the underlying cache store and returns the currently
 * cached keys together with their expiry time.
 *
 * Supports the "file", "database" and "redis" drivers.
 */
class CacheKeyService
{
    public static function all(): array
    {
        $store = config('cache.default');
        $driver = Cache::getStore();

        if ($driver instanceof \Illuminate\Cache\TaggableStore && $store === 'redis') {
            return self::fromRedis();
        }

        if ($store === 'database') {
            return self::fromDatabase();
        }

        if ($store === 'file') {
            return self::fromFile();
        }

        return [];
    }

    protected static function fromDatabase(): array
    {
        $table = config('cache.stores.database.table', 'cache');

        if (! DB::connection(config('cache.stores.database.connection'))->getSchemaBuilder()->hasTable($table)) {
            return [];
        }

        return DB::connection(config('cache.stores.database.connection'))
            ->table($table)
            ->get(['key', 'expiration'])
            ->map(function ($row) {
                return [
                    'key' => $row->key,
                    'expires_at' => $row->expiration ? date('Y-m-d H:i:s', $row->expiration) : 'forever',
                ];
            })
            ->toArray();
    }

    protected static function fromFile(): array
    {
        $path = storage_path('framework/cache/data');
        $keys = [];

        if (! is_dir($path)) {
            return $keys;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $contents = file_get_contents($file->getPathname());
            $unserialized = @unserialize($contents);

            if (! is_array($unserialized) || count($unserialized) < 2) {
                continue;
            }

            $expiration = $unserialized[0];
            $keys[] = [
                'key' => ltrim(str_replace($path, '', $file->getPathname()), DIRECTORY_SEPARATOR),
                'expires_at' => $expiration ? date('Y-m-d H:i:s', $expiration) : 'forever',
            ];
        }

        return $keys;
    }

    protected static function fromRedis(): array
    {
        try {
            $keys = Cache::getStore()->getRedis()->keys('*');
        } catch (\Throwable $e) {
            return [];
        }

        return collect($keys)->map(fn ($k) => ['key' => $k, 'expires_at' => 'n/a'])->toArray();
    }
}
