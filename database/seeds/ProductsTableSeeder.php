<?php

use Illuminate\Database\Seeder;
use CodeShopping\Product;
use CodeShopping\Category;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = Category::all();
        factory(Product::class, 30)
            ->create()
            ->each(function(Product $product) use($categories){
                $categoryId = $categories->random()->id;
                $product->categories()->attach($categoryId);
            });
    }
}
