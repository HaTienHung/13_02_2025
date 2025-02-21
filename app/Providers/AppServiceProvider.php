<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderInterface;
use App\Repositories\OrderItem\OrderItemInterface;
use App\Repositories\OrderItem\OrderItemRepository;
use App\Repositories\Inventory\InventoryInterface;
use App\Repositories\Inventory\InventoryRepository;


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
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(InventoryInterface::class, InventoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
