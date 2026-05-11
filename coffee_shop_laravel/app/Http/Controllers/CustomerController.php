<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Hiển thị trang chủ giới thiệu quán dành cho khách hàng.
     * Tự động xử lý dữ liệu để đảm bảo giao diện không bao giờ bị trống.
     */
    public function index()
    {
        // 1. Lấy thông tin cấu hình quán (Tên, địa chỉ, hotline, giờ mở cửa)
        $shop_setting = DB::table('shop_settings')->first();

        // 2. Lấy danh sách 4 món "Món Ngon Đề Xuất"
        // Ưu tiên lấy những món được Admin tích chọn is_best_seller = 1
        $bestSellers = Product::where('status', 'active')
                                ->where('is_best_seller', 1) 
                                ->with('category')
                                ->latest()
                                ->limit(4)
                                ->get();

        // LOGIC DỰ PHÒNG: Nếu chưa có món nào được chọn làm Best Seller
        // Hệ thống sẽ tự động lấy 4 món bất kỳ đang hoạt động để trang chủ không bị trống
        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::where('status', 'active')
                                    ->with('category')
                                    ->inRandomOrder() // Lấy ngẫu nhiên để đổi mới giao diện
                                    ->limit(4)
                                    ->get();
        }

        // 3. Lấy thực đơn đề xuất phân loại theo Danh mục (Dotted Menu)
        // Lấy 3 danh mục đầu tiên, mỗi danh mục lấy tối đa 6 món
        $menuCategories = Category::where('status', 'active')
                                    ->with(['products' => function($query) {
                                        $query->where('status', 'active')->limit(6);
                                    }])
                                    ->limit(3) 
                                    ->get();

        /**
         * Trả về view kèm dữ liệu
         */
        return view('customer.index', compact('shop_setting', 'bestSellers', 'menuCategories'));
    }

    /**
     * Trang giới thiệu chi tiết (About Us)
     */
    public function about()
    {
        $shop_setting = DB::table('shop_settings')->first();
        return view('customer.about', compact('shop_setting'));
    }
}