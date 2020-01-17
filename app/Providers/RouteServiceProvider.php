<?php

namespace CodeShopping\Providers;

use CodeShopping\User;
use CodeShopping\Product;
use CodeShopping\Category;
use Illuminate\Http\Request;
use CodeShopping\Common\OnlyTrashed;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    use OnlyTrashed;
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'CodeShopping\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();

        Route::bind('category', function($value){
            //dump and die
            //dd($value);
            $collection = Category::whereId($value)->orWhere('slug',$value)->get();
            return $collection->first();
        });

        Route::bind('product', function($value){
            $query = Product::query();
            $request =  app(Request::class);
            $query = $this->onlyTrashedIfRequested($request, $query);
            $collection = $query->whereId($value)->orWhere('slug',$value)->get();
            return $collection->first(); //null
        });

        Route::bind('user', function($value){
            $query = User::query();
            $request =  app(Request::class);
            $query = $this->onlyTrashedIfRequested($request, $query);
            return $query->find($value); //null
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
