<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        $productsData = collect(json_decode(file_get_contents(base_path('tests/stubs/products.json')), true)['products'])
            ->map(function (array $product) use ($categories) {
                return [
                    'sku' => $product['sku'],
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'category_id' => $categories->where(fn (Category $category) => $category->name === $product['category'])->sole()['id']
                ];
            });

        Product::insert($productsData->all());
    }
}
