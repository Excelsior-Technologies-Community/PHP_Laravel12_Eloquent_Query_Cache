    <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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

// Soft deletes (Trash)
Route::get('/trash', [ProductController::class, 'trash'])
    ->name('products.trash');

Route::post('/restore/{id}', [ProductController::class, 'restore'])
    ->name('products.restore');

Route::delete('/force-delete/{id}', [ProductController::class, 'forceDelete'])
    ->name('products.forceDelete');

// Bulk CSV Import
Route::get('/import', [ProductController::class, 'import'])
    ->name('products.import');

Route::post('/import', [ProductController::class, 'importStore'])
    ->name('products.importStore');

// Cache management
Route::get('/clear-cache', [ProductController::class, 'clearCache'])
    ->name('products.clearCache');

Route::get('/export-products', [ProductController::class, 'exportCSV'])
    ->name('products.export');

Route::get('/cache-status', [ProductController::class, 'cacheStatus'])
    ->name('products.cacheStatus');

Route::get('/cache-keys', [ProductController::class, 'cacheKeys'])
    ->name('products.cacheKeys');

Route::get('/warmup-cache', [ProductController::class, 'warmUpCache'])
    ->name('products.warmUp');

Route::get('/analytics', [ProductController::class, 'analytics'])
    ->name('products.analytics');

// Categories management
Route::get('/categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::post('/categories', [CategoryController::class, 'store'])
    ->name('categories.store');

Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
    ->name('categories.destroy');
