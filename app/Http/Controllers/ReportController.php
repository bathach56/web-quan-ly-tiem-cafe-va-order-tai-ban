<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // 1. Nhận ngày từ bộ lọc (Nếu không chọn, mặc định từ đầu tháng đến hôm nay)
        // Dùng Carbon để xử lý ngày giờ chuẩn xác
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        try {
            // 2. Truy vấn gộp nhóm theo từng ngày từ bảng orders
            // Lưu ý: Đã sửa 'total_price' thành 'total_amount' cho khớp với Database
            $reports = DB::table('orders')
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(id) as total_orders'),
                    DB::raw('SUM(total_amount) as daily_revenue') 
                )
                ->where('status', 'completed') // CHỈ TÍNH ĐƠN ĐÃ HOÀN THÀNH
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date', 'desc')
                ->get();

            // 3. Thống kê món bán chạy nhất trong khoảng thời gian này (Cộng thêm cho pro)
            $topProducts = DB::table('order_details')
                ->join('products', 'order_details.product_id', '=', 'products.id')
                ->join('orders', 'order_details.order_id', '=', 'orders.id')
                ->select('products.name', DB::raw('SUM(order_details.quantity) as total_qty'))
                ->where('orders.status', 'completed')
                ->whereDate('orders.created_at', '>=', $startDate)
                ->whereDate('orders.created_at', '<=', $endDate)
                ->groupBy('products.name')
                ->orderBy('total_qty', 'desc')
                ->limit(5)
                ->get();

            // 4. Tính tổng của cả kỳ báo cáo
            $totalRevenue = $reports->sum('daily_revenue');
            $totalOrders = $reports->sum('total_orders');

        } catch (\Exception $e) {
            // Log lỗi nếu cần thiết để debug: \Log::error($e->getMessage());
            $reports = collect();
            $topProducts = collect();
            $totalRevenue = 0;
            $totalOrders = 0;
        }

        // 5. Trả dữ liệu về View
        return view('reports.index', compact(
            'reports', 
            'topProducts', 
            'totalRevenue', 
            'totalOrders', 
            'startDate', 
            'endDate'
        ));
    }
}