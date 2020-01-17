<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Product;
use CodeShopping\Category;
use Illuminate\Http\Request;
use CodeShopping\Common\OnlyTrashed;
use Illuminate\Database\Eloquent\Builder;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Http\Requests\ProductRequest;
use CodeShopping\Http\Resources\ProductResource;
use CodeShopping\Http\Filters\ProductFilter;

class ProductController extends Controller
{
    use OnlyTrashed;
    
    public function index(Request $request)
    {
        /*$query = Product::query();
        $query = $this->onlyTrashedIfRequested($request, $query);
        $products = $query->paginate(10);
        return ProductResource::collection($products);*/

        $filter = app(ProductFilter::class);
        $query = Product::query();
        $query = $this->onlyTrashedIfRequested($request, $query);
        $filterQuery = $query->filtered($filter);
        $products = $filter->hasFilterParameter() ?
           $filterQuery->get() :
           $filterQuery->paginate(10);
        return ProductResource::collection($products);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());
        $product->refresh();//active
        return new ProductResource($product);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->all());
        $product->save();

        return new ProductResource($product);

        //return response([], 204);
    }

    public function destroy(Product $product, Category $category)
    {
        $product->categories()->detach($category->id);
        $product->delete();
        return response()->json([], 204);
    }

    public function restore(Product $product)
    {
        $product->restore();
        return response()->json([], 204);
    }

}
