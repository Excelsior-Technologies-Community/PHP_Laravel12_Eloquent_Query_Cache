<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheLog extends Model
{
    protected $fillable = [
        'type',
        'query'
    ];
}