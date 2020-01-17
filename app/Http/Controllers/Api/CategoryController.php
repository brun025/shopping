<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Category;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Http\Filters\CategoryFilter;
use CodeShopping\Http\Requests\CategoryRequest;
use CodeShopping\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    
    public function index(Request $request)
    {
        $filter = app(CategoryFilter::class);
        $filterQuery = Category::filtered($filter);
        if($request->has('all')) $categories = $filterQuery->get();
        else $categories = $filterQuery->paginate(5);
        return CategoryResource::collection($categories);
        //se passa o parâmetro all vai pegar todas as categorias senão vai paginar
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());
        $category->refresh();
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $category->fill($request->all());
        $category->save();

        return new CategoryResource($category);

        //return response([], 204);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([], 204);
    }
}
