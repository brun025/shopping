<?php

namespace CodeShopping\Providers;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use CodeShopping\ProductInput;
use CodeShopping\ProductOutput;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ProductInput::created(function($input){
            $product = $input->product;
            $product->stock += $input->amount; 
            $product->save();
        });

        ProductOutput::created(function($input){
            $product = $input->product;
            $product->stock -= $input->amount;
            if($product->stock < 0){
                throw new \Exception("Estoque de {$product->name} não pode ser negativo");
            } 
            $product->save();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Firebase::class, function(){
            $serviceAccount = Firebase\ServiceAccount::fromJsonFile(base_path('codeshopping-e2464-firebase-adminsdk-zmw24-d74134650e.json'));
            return (new Factory())->withServiceAccount($serviceAccount)->create();
        });
    }
}
