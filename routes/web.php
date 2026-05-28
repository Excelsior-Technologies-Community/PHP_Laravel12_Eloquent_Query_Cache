<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/create', [ProductController::class, 'create'])
    ->name('products.create');

Route::post('/store', [ProductController::class, 'store'])
    ->name('products.store');

Route::get('/edit/{id}', [ProductController::class, 'edit'])
    ->name('products.edit');

Route::put('/update/{id}', [ProductController::class, 'update'])
    ->name('products.update');

Route::delete('/delete/{id}', [ProductController::class, 'destroy'])
    ->name('products.destroy');

Route::get('/clear-cache', [ProductController::class, 'clearCache'])
    ->name('products.clearCache');