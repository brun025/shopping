<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Product;
use CodeShopping\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Http\Requests\ProductCategoryRequest;
use CodeShopping\Http\Resources\ProductCategoryResource;

class ProductCategoryController extends Controller
{
    
    public function index(Product $product)
    {
        return new ProductCategoryResource($product);
    }

    public function store(ProductCategoryRequest $request, Product $product)
    {
        $changed = $product->categories()->sync($request->categories);
        $categoriesAttachedId = $changed['attached'];
        //var COLLECTION categories
        $categories = Category::whereIn('id', $categoriesAttachedId)->get(); //WHERE in IN (1,3)
        //return $categories;
        return $categories->count() ? response()->json(new ProductCategoryResource($product), 201) : [];
    }

    public function destroy(Product $product, Category $category)
    {
        $product->categories()->detach($category->id);
        return response()->json([], 204);
    }
}
