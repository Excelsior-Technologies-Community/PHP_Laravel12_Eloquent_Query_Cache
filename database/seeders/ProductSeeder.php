<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Electronics', 'Books', 'Clothing', 'Grocery'];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        $cats = Category::all();

        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'name' => fake()->unique()->words(2, true),
                'price' => fake()->randomFloat(2, 10, 5000),
                'category_id' => $cats->random()->id,
            ]);
        }
    }
}
