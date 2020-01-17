<?php
declare(strict_types=1);

namespace CodeShopping;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Mnabialek\LaravelEloquentFilter\Traits\Filterable;

class Category extends Model
{
    use Sluggable, Filterable;
    protected $fillable = ['name', 'active'];

    public function sluggable(): array
    {
        return[
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
