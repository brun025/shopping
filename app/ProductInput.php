<?php

namespace CodeShopping;

use CodeShopping\Product;
use Illuminate\Database\Eloquent\Model;
use Mnabialek\LaravelEloquentFilter\Traits\Filterable;

class ProductInput extends Model
{
    use Filterable;
    
    protected $fillable = ['amount', 'product_id'];

    //many-to-one
    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }
}
