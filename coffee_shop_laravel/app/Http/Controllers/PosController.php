<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CoffeeTable;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    /**
     * Hiển thị giao diện máy POS cho nhân viên
     */
    public function index()
    {
        // Chặn Admin vào giao diện bán hàng nếu cần
        if (Auth::user()->position === 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Admin vui lòng xem báo cáo, không trực tiếp bán hàng.');
        }

        $categories = Category::where('status', 'active')->get();
        $products = Product::where('status', 'active')->with('category')->get();
        $tables = CoffeeTable::orderBy('name', 'asc')->get();

        return view('pos.index', compact('categories', 'products', 'tables'));
    }

    /**
     * Lấy thông tin đơn hàng hiện tại của bàn (Dùng cho Polling/QR)
     */
    public function getTableOrder($id)
    {
        try {
            $order = Order::where('table_id', $id)
                ->where('payment_status', 'unpaid')
                ->whereIn('status', ['pending', 'unconfirmed', 'preparing'])
                ->with(['orderDetails.product']) 
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Bàn này hiện đang trống.']);
            }

            // Map dữ liệu để JavaScript nhận đúng key 'id'
            $details = $order->orderDetails->map(function ($item) {
                return [
                    'id'        => $item->product_id, // Quan trọng: Đây là key 'id' mà JS cần
                    'detail_id' => $item->id,
                    'name'      => $item->product->name ?? 'Món đã bị xóa',
                    'price'     => (int)$item->price,
                    'qty'       => $item->quantity,
                ];
            });

            return response()->json([
                'success'  => true,
                'order_id' => $order->id,
                'status'   => $order->status,
                'details'  => $details
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Xác nhận đơn hàng gửi xuống bếp & Tắt trạng thái pending
     */
    public function sendToKitchen(Request $request)
    {
        $request->validate(['order_id' => 'required|exists:orders,id']);

        try {
            DB::beginTransaction();
            
            $order = Order::findOrFail($request->order_id);
            $order->update(['status' => 'preparing']);
            
            // Chuyển trạng thái bàn sang 'occupied' (màu đỏ)
            CoffeeTable::where('id', $order->table_id)->update(['status' => 'occupied']);
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Đã gửi đơn xuống bếp thành công!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * XỬ LÝ THANH TOÁN (Fix lỗi Undefined array key "id")
     */
    public function checkout(Request $request) 
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'table_id' => 'required',
            'cart' => 'required|array',
            'payment_method' => 'required'
        ]);

        DB::beginTransaction();
        try {
            // 2. Tìm đơn hàng cũ (nếu có) hoặc tạo đơn mới
            $order = null;
            if ($request->order_id) {
                $order = Order::find($request->order_id);
            }

            if (!$order) {
                $order = new Order();
                $order->table_id = $request->table_id;
                $order->order_date = now();
            }

            // 3. Cập nhật thông tin đơn hàng theo Database thật
            $order->user_id = Auth::id(); // Dùng user_id thay vì employee_id
            $order->total_amount = $request->total_amount;
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'paid';
            $order->status = 'completed';
            $order->save();

            // 4. Xử lý chi tiết món ăn (Xóa cũ nạp mới để đảm bảo tính đồng nhất)
            OrderDetail::where('order_id', $order->id)->delete();

            foreach ($request->cart as $item) {
                // KIỂM TRA KEY 'id' TRƯỚC KHI LƯU
                if (!isset($item['id'])) {
                    throw new \Exception("Dữ liệu giỏ hàng không hợp lệ (Thiếu ID sản phẩm).");
                }

                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price']
                ]);
            }

            // 5. Giải phóng bàn về trạng thái trống
            CoffeeTable::where('id', $request->table_id)->update(['status' => 'available']);

            DB::commit();
            
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Thanh toán hoàn tất!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi Server: ' . $e->getMessage()
            ], 500); 
        }
    }

    /**
     * In hóa đơn HTML
     */
    public function printReceipt($id)
    {
        // Load cả user (nhân viên) và table để in bill đầy đủ
        $order = Order::with(['orderDetails.product', 'table', 'user'])->findOrFail($id);
        return view('pos.receipt', compact('order'));
    }
}