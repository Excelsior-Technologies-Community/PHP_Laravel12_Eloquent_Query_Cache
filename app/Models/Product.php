<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Rennokki\QueryCache\Traits\QueryCacheable;

class Product extends Model
{
    use QueryCacheable;

    protected $fillable = ['name', 'price'];

    // 🔥 Cache settings
    protected $cacheFor = 10; // seconds
    protected $flushCacheOnUpdate = true;
    protected static $flushCacheOnCreate = true;
}