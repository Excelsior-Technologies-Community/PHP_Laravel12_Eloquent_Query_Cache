<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Product extends Model
{
    use QueryCacheable;

    protected $fillable = [
        'name',
        'price'
    ];

    // Cache for 10 seconds
    public $cacheFor = 10;

    // Auto clear cache
    protected static $flushCacheOnUpdate = true;
    protected static $flushCacheOnDelete = true;
    protected static $flushCacheOnCreate = true;
}