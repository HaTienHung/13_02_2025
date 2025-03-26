<?php

namespace App\Providers;

use App\Models\Product;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Product\ProductRepository;
use Illuminate\http\Request;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
