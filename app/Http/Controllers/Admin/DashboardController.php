<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CustomBurger;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        // Total revenue for CURRENT month only (completed orders)
        $totalRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Total completed orders for CURRENT month only
        $totalOrders = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Growth percentage (compare CURRENT month to LAST month completed orders)
        $lastMonthOrders = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        
        $growthPercentage = $lastMonthOrders > 0 
            ? (($totalOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
            : ($totalOrders > 0 ? 100 : 0);

        // Daily sales for bar chart (last 7 days)
        $dailySales = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $total = Order::where('status', 'completed')
                ->whereDate('created_at', $date->toDateString())
                ->sum('total_amount');
            
            $dailySales->push([
                'day' => $date->format('N'), // 1 = Monday, 7 = Sunday
                'date' => $date->toDateString(),
                'total' => (float) $total,
            ]);
        }

        // Monthly orders for line chart (last 7 months)
        $monthlyOrders = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyOrders->push([
                'month' => (int) $date->format('m'),
                'year' => (int) $date->format('Y'),
                'count' => $count,
            ]);
        }

        // Popular products (most ordered burgers)
        $popularProducts = DB::table('order_items')
            ->select('burger_name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(quantity) as total_sold'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->where('orders.status', '!=', 'rejected')
            ->groupBy('burger_name')
            ->orderBy('total_sold', 'desc')
            ->limit(12)
            ->get()
            ->map(function ($item) {
                // Calculate average price
                $avgPrice = DB::table('order_items')
                    ->where('burger_name', $item->burger_name)
                    ->avg('price');
                
                return [
                    'name' => $item->burger_name,
                    'orders' => $item->order_count,
                    'sold' => $item->total_sold,
                    'price' => (float) $avgPrice,
                ];
            });

        return Inertia::render('Admin/Dashboard/Index', [
            'stats' => [
                'totalRevenue' => (float) $totalRevenue,
                'totalOrders' => $totalOrders,
                'growthPercentage' => round($growthPercentage, 1),
            ],
            'dailySales' => $dailySales,
            'monthlyOrders' => $monthlyOrders,
            'popularProducts' => $popularProducts,
        ]);
    }
}