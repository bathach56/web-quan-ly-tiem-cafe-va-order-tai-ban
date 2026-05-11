<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Order, CoffeeTable, Product, Category};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Doanh thu hom nay (status = completed)
        $todayRevenue = Order::where('status', 'completed')
                            ->whereDate('created_at', Carbon::today())
                            ->sum('total_amount');

        // 2. So luong mon dang kinh doanh
        $totalProducts = Product::where('status', 'active')->count();

        // 3. So ban dang co khach (status = occupied)
        $activeTables = CoffeeTable::where('status', 'occupied')->count();

        // 4. Danh muc menu
        $totalCategories = Category::count();

        // 5. Du lieu bieu do duong (7 ngay qua)
        $chartData = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 6. Du lieu bieu do tron (Top danh muc ban chay)
        $pieChartData = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->select('categories.name', DB::raw('SUM(order_details.quantity) as total_qty'))
            ->where('orders.status', 'completed')
            ->groupBy('categories.name')
            ->get();

        return view('dashboard', compact(
            'todayRevenue', 'totalProducts', 'activeTables', 
            'totalCategories', 'chartData', 'pieChartData'
        ));
    }
}