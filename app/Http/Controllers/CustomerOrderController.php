<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoffeeTable; // Hoặc Table tùy theo tên Model của bạn
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    /**
     * Hiển thị giao diện gọi món cho khách hàng qua mã QR
     * Route: /menu/table/{id}
     */
    public function index($id)
    {
        // 1. Lấy thông tin bàn dựa trên ID từ URL
        $table = CoffeeTable::findOrFail($id);

        // 2. Lấy danh sách danh mục có món ăn
        $categories = Category::all();

        // 3. Lấy danh sách sản phẩm đang kinh doanh (active)
        $products = Product::where('status', 'active')
                           ->with('category')
                           ->get();

        // Trả về view order xịn xò mà chúng ta đã làm
        return view('customer.order', compact('table', 'categories', 'products'));
    }

    /**
     * Xử lý lưu đơn hàng khách gửi từ điện thoại
     * Route: /menu/order
     */
    public function storeOrder(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'table_id' => 'required|exists:coffee_tables,id',
            'cart'     => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Tính tổng tiền đơn hàng từ giỏ hàng gửi lên
            $totalAmount = 0;
            foreach ($request->cart as $item) {
                $totalAmount += $item['price'] * $item['qty'];
            }

            // 2. Tạo đơn hàng mới trong bảng orders
            $order = Order::create([
                'table_id'       => $request->table_id,
                'total_amount'   => $totalAmount,
                'status'         => 'pending', // Trạng thái chờ xử lý
                'payment_status' => 'unpaid',  // Chưa thanh toán
                'order_type'     => 'qr_code',  // Đánh dấu đơn từ QR
                'note'           => 'Khách đặt món qua QR tại bàn',
            ]);

            // 3. Lưu chi tiết từng món vào bảng order_details
            foreach ($request->cart as $item) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                ]);
            }

            // 4. CẬP NHẬT TRẠNG THÁI BÀN SANG 'WAITING'
            // Đây là bước quan trọng để máy POS của nhân viên nhấp nháy báo đơn mới
            $table = CoffeeTable::find($request->table_id);
            $table->update(['status' => 'waiting']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gửi đơn hàng thành công! Vui lòng đợi nhân viên phục vụ.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
}