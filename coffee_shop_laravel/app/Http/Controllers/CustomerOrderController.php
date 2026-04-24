<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoffeeTable;
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

        // 2. Lấy danh mục có trạng thái active để hiển thị menu
        $categories = Category::where('status', 'active')->get();

        // 3. Lấy sản phẩm active kèm thông tin danh mục
        $products = Product::where('status', 'active')
                            ->with('category')
                            ->get();

        // Trả về view giao diện khách hàng (Thịnh thiết kế Mobile-First cho đẹp nhé)
        return view('customer.order', compact('table', 'categories', 'products'));
    }

    /**
     * Xử lý lưu đơn hàng khách gửi từ điện thoại (Hỗ trợ cộng dồn/gọi thêm)
     * Route: /menu/order
     */
    public function storeOrder(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào chuẩn xác
        $request->validate([
            'table_id' => 'required|exists:coffee_tables,id',
            'cart'     => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Tìm đơn hàng hiện tại của bàn (Chưa thanh toán và chưa hoàn thành)
            // Logic: Nếu khách đang ngồi đó mà gọi thêm, ta sẽ gộp chung vào 1 hóa đơn
            $order = Order::where('table_id', $request->table_id)
                          ->where('payment_status', 'unpaid')
                          ->whereIn('status', ['pending', 'preparing', 'unconfirmed'])
                          ->first();

            // 2. Tính toán tổng số tiền của lượt gọi món này
            $newTotalFromCart = 0;
            foreach ($request->cart as $item) {
                $newTotalFromCart += $item['price'] * $item['qty'];
            }

            if ($order) {
                // --- TRƯỜNG HỢP GỌI THÊM MÓN ---
                $order->total_amount += $newTotalFromCart;
                $order->save();

                foreach ($request->cart as $item) {
                    // Kiểm tra xem món này đã có trong hóa đơn chưa để cộng dồn số lượng
                    $detail = OrderDetail::where('order_id', $order->id)
                                         ->where('product_id', $item['id'])
                                         ->first();

                    if ($detail) {
                        $detail->quantity += $item['qty'];
                        $detail->save();
                    } else {
                        // Nếu là món mới hoàn toàn trong đơn cũ
                        OrderDetail::create([
                            'order_id'   => $order->id,
                            'product_id' => $item['id'],
                            'quantity'   => $item['qty'],
                            'price'      => $item['price'],
                        ]);
                    }
                }
            } else {
                // --- TRƯỜNG HỢP MỞ ĐƠN MỚI ---
                $order = Order::create([
                    'table_id'       => $request->table_id,
                    'total_amount'   => $newTotalFromCart,
                    'status'         => 'pending', 
                    'payment_status' => 'unpaid',
                    'note'           => 'Khách gọi món qua QR',
                    'order_date'     => now(),
                ]);

                foreach ($request->cart as $item) {
                    OrderDetail::create([
                        'order_id'   => $order->id,
                        'product_id' => $item['id'],
                        'quantity'   => $item['qty'],
                        'price'      => $item['price'],
                    ]);
                }
            }

            // 3. KÍCH HOẠT CHẤM ĐỎ: Cập nhật trạng thái bàn sang 'pending'
            // Trạng thái này sẽ kích hoạt Badge thông báo real-time trên màn hình Staff
            $table = CoffeeTable::find($request->table_id);
            $table->update(['status' => 'pending']); 

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Hệ thống đã nhận được yêu cầu của bạn. Chúc bạn ngon miệng!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}