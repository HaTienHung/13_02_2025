<?php

namespace App\Providers;


use App\Models\Product;
use App\Providers\ProductServiceProvider;
use App\Repositories\BaseRepository;
use App\Repositories\BaseRepositoryInterface;
use App\Repositories\Cart\CartInterface;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Category\CategoryInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Dashboard\DashboardInterface;
use App\Repositories\Dashboard\DashboardRepository;
use App\Repositories\Inventory\InventoryInterface;
use App\Repositories\Inventory\InventoryRepository;
use App\Repositories\Order\OrderInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderItem\OrderItemInterface;
use App\Repositories\OrderItem\OrderItemRepository;
use App\Repositories\Product\ProductInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\User\UserInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $repositories = [
            OrderInterface::class => OrderRepository::class,
            UserInterface::class => UserRepository::class,
            ProductInterface::class => ProductRepository::class,
            OrderItemInterface::class => OrderItemRepository::class,
            CategoryInterface::class => CategoryRepository::class,
            InventoryInterface::class => InventoryRepository::class,
            CartInterface::class => CartRepository::class,
            DashboardInterface::class => DashboardRepository::class,
        ];

        foreach ($repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
