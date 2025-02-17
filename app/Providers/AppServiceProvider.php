<?php

namespace App\Providers;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Support\ServiceProvider;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderInterface;
use App\Repositories\OrderItem\OrderItemInterface;
use App\Repositories\OrderItem\OrderItemRepository;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(OrderItemInterface::class, OrderItemRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::policy(Order::class, OrderPolicy::class);
        // Gate::policy(Product::class, BasePolicy::class);
        // Gate::policy(Category::class, BasePolicy::class);
    }
}
