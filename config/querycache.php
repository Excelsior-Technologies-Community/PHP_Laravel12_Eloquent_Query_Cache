<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Product Cache TTL (seconds)
    |--------------------------------------------------------------------------
    |
    | How long product listing queries stay cached. Override with CACHE_TTL
    | in your .env file.
    |
    */

    'ttl' => env('CACHE_TTL', 10),

    /*
    |--------------------------------------------------------------------------
    | Cache Version
    |--------------------------------------------------------------------------
    |
    | Bumping this (or calling the cache service invalidation) changes the
    | cache key prefix, instantly invalidating every cached product query
    | regardless of the underlying cache driver. The live version is also
    | tracked in the cache store itself so it survives restarts.
    |
    */

    'version' => env('CACHE_VERSION', 1),

];
