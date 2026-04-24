<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Hiển thị trang báo cáo doanh thu (Giao diện chính)
     */
    public function index(Request $request)
    {
        // 1. Xử lý ngày tháng (Mặc định từ đầu tháng đến ngày hiện tại)
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        // 2. Lấy dữ liệu báo cáo tổng hợp theo ngày
        $reports = Order::whereBetween('order_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid') // Chỉ tính các đơn đã thanh toán thành công
            ->select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as daily_revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // 3. Tính toán tổng số liệu cho các thẻ Summary (Doanh thu, Đơn hàng)
        $totalRevenue = $reports->sum('daily_revenue');
        $totalOrders = $reports->sum('total_orders');

        // 4. Lấy Top 5 món bán chạy nhất để hiển thị cột bên phải
        $topProducts = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->whereBetween('orders.order_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('orders.payment_status', 'paid')
            ->select('products.name', DB::raw('SUM(order_details.quantity) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        return view('reports.index', compact('reports', 'totalRevenue', 'totalOrders', 'topProducts', 'startDate', 'endDate'));
    }

    /**
     * XUẤT FILE EXCEL
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        // Kiểm tra dữ liệu trước khi cho tải để tránh file rỗng
        $exists = Order::whereBetween('order_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                       ->where('payment_status', 'paid')
                       ->exists();

        if (!$exists) {
            return back()->with('error', 'Không có dữ liệu thanh toán trong khoảng thời gian này để xuất Excel!');
        }

        return Excel::download(new OrdersExport($startDate, $endDate), "doanh-thu-hutech-coffee-{$startDate}.xlsx");
    }

    /**
     * XUẤT FILE PDF (Đã tối ưu font và dữ liệu)
     */
    public function exportPDF(Request $request)
    {
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        // Lấy danh sách đơn hàng chi tiết kèm thông tin Bàn
        $orders = Order::whereBetween('order_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->with(['table']) // Tránh lỗi null khi gọi tên bàn trong file PDF
            ->orderBy('order_date', 'asc')
            ->get();

        $totalRevenue = $orders->sum('total_amount');

        // Khởi tạo PDF từ View và cấu hình thông số chuẩn
        $pdf = Pdf::loadView('reports.pdf', [
            'orders' => $orders,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue
        ])
        ->setPaper('a4', 'portrait') // Khổ giấy A4 dọc
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,      // Cho phép load ảnh/logo từ public path
            'defaultFont' => 'DejaVu Sans'  // Ép dùng font hỗ trợ Tiếng Việt (DejaVu Sans có sẵn trong thư viện)
        ]);

        return $pdf->download("bao-cao-doanh-thu-{$startDate}.pdf");
    }
}