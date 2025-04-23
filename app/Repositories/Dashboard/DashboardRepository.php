<?php


namespace App\Repositories\Dashboard;

use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Collection;

class DashboardRepository implements DashboardInterface
{
    public function getStats(): array
    {
        $totalCustomers = User::ofRole(User::$user)->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');

        return [
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
        ];
    }
    public function latestInvoice()
    {
        return Order::with('user') // nếu muốn có thông tin user
        ->latest() // tương đương ->orderBy('created_at', 'desc')
        ->take(5)
            ->get();
    }
    public function getRevenueByDay()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        return DB::table('orders')
            ->selectRaw("DATE(created_at) as day, DAYNAME(created_at) as weekday, SUM(total_price) as revenue")
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw("DATE(created_at), DAYNAME(created_at)"))
            ->orderBy('day')
            ->get();

    }
}
