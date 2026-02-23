# PHP_Laravel12_Eloquent_Query_Cache
```php
Laravel 12 based project demonstrating native Eloquent Query Caching using Cache::remember() with automatic cache invalidation and Blade UI.
```
# Step 1: Install Laravel 12 – Create Project
Open Terminal / CMD:
```php
composer create-project laravel/laravel:^12.0 PHP_Laravel12_Eloquent_Query_Cache
```
Move to project folder:
```php
cd PHP_Laravel12_Eloquent_Query_Cache
```
Generate application key:
```php
php artisan key:generate
```

# Step 2: Setup Database (.env File)
Open .env file and configure database credentials:
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=laravel12_query_cache
DB_USERNAME=root
DB_PASSWORD=
```
Create database in MySQL / phpMyAdmin:
```php
CREATE DATABASE laravel12_query_cache;
```

# Step 3: Run Default Migrations
```php
php artisan migrate
```

# Step 4: Verify Cache Configuration
Open .env file:
```php
CACHE_STORE=file
```
# Explanation
```php
- Uses Laravel’s native file-based cache
- No third-party packages required
- Perfect for demo & learning purposes
```
# Step 5: Configure User Model with Cached Query
Path:
```php
app/Models/Product.php
```
```php
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
```
# Explanation
```php
- Cache::remember() stores query result in cache
- First request → database query
- Next requests → cache hit
- Cache is cleared automatically when data changes
```
# Step 6: Create Controller
Create controller:
```php
php artisan make:controller ProductController
```
Path:
```php
app/Http/Controllers/ProductController.php
```

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // 🔥 THIS QUERY IS AUTO CACHED
        $products = Product::orderBy('id', 'desc')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
        ]);

        Product::create($request->only('name', 'price'));

        // 🔥 Cache auto flushed by package
        return redirect()->route('products.index')
            ->with('success', 'Product Added (Cache Auto Cleared)');
    }
}
```
# Explanation
```php
- Controller fetches data only from cached model method
- No direct database query inside controller
```

# Step 7: Define Web Routes
Path:
```php
routes/web.php
```
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/store', [ProductController::class, 'store'])->name('products.store');
```
# Step 8: Create Blade UI
Create directory:
```php
resources/views/products
```
Create file:
```php
resources/views/products/index.blade.php
```
```php
@extends('layouts.app')

@section('content')

<div class="top-bar">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        + Add Product
    </a>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<table>
    <thead>
        <tr>
            <th width="80">ID</th>
            <th>Product Name</th>
            <th width="150">Price</th>
            <th width="200">Created At</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>₹ {{ number_format($product->price, 2) }}</td>
                <td>{{ $product->created_at->format('d-m-Y h:i A') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align:center">
                    No products found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>



@endsection
```
# Explanation
```php
- UI always displays cached data
- No database logic inside Blade files
- Clean separation of concerns
```

# Step 9: Test Cache Behavior

Run Laravel development server:
```php
php artisan serve
```
Open browser:
```php
http://127.0.0.1:8000/create
```
<img width="1203" height="458" alt="image" src="https://github.com/user-attachments/assets/8034d182-2b28-4470-953b-e2988b934062" />

```php
http://127.0.0.1:8000/
```

<img width="1328" height="429" alt="image" src="https://github.com/user-attachments/assets/a19f8d93-3621-4b1b-a7f2-b390bdc9ad04" />



