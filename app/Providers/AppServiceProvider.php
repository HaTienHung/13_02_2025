<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $repositories = [
            \App\Repositories\BaseRepositoryInterface::class => \App\Repositories\BaseRepository::class,
            \App\Repositories\Product\ProductInterface::class => \App\Repositories\Product\ProductRepository::class,
            \App\Repositories\Order\OrderInterface::class => \App\Repositories\Order\OrderRepository::class,
            \App\Repositories\OrderItem\OrderItemInterface::class => \App\Repositories\OrderItem\OrderItemRepository::class,
            \App\Repositories\Category\CategoryInterface::class => \App\Repositories\Category\CategoryRepository::class,
            \App\Repositories\Inventory\InventoryInterface::class => \App\Repositories\Inventory\InventoryRepository::class,
            \App\Repositories\Cart\CartInterface::class => \App\Repositories\Cart\CartRepository::class,
        ];

        foreach ($repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
