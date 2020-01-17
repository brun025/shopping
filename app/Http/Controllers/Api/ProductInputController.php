<?php

namespace CodeShopping\Http\Controllers\Api;

use Illuminate\Http\Request;
use CodeShopping\ProductInput;
use CodeShopping\Http\Controllers\Controller;
use CodeShopping\Http\Filters\ProductInputFilter;
use CodeShopping\Http\Requests\ProductInputRequest;
use CodeShopping\Http\Resources\ProductInputResource;

class ProductInputController extends Controller
{
    
    public function index()
    {
        $filter = app(ProductInputFilter::class);
        $filterQuery = ProductInput::with('product')->filtered($filter);
        $inputs = $filterQuery->paginate();//carregamento prematura(eager loading)
        //padrão do laravel lazy loading
        return ProductInputResource::collection($inputs);
    }

    public function store(ProductInputRequest $request)
    {
        $input = ProductInput::create($request->all());
        return new ProductInputResource($input);
    }

    public function show(ProductInput $input)//nome da variável igual ao singular route::resource
    {
        return new ProductInputResource($input);
    }

}
