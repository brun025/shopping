<?php
declare(strict_types=1);

namespace CodeShopping;

use CodeShopping\ProductPhoto;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mnabialek\LaravelEloquentFilter\Traits\Filterable;

class Product extends Model
{
    use Sluggable, SoftDeletes, Filterable;

    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'price', 'description', 'active'];

    public function sluggable(): array
    {
        return[
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function photos(){
        return $this->hasMany(ProductPhoto::class);
    }
    
}
