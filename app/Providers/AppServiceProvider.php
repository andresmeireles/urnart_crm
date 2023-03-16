<?php

namespace App\Providers;

use App\Actions\Api\Auth\LoginInterface;
use App\Actions\Api\Delivery\CreateDeliveryInterface;
use App\Actions\Api\Product\CreateProductInterface;
use App\Actions\Auth\Login;
use App\Actions\Delivery\Create;
use App\Actions\Product\CreateProduct;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LoginInterface::class, static fn () => new Login());
        $this->app->bind(CreateProductInterface::class, static fn () => new CreateProduct());
        $this->app->bind(CreateDeliveryInterface::class, static fn () => new Create());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
