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
     * Hiển thị giao diện máy POS
     * Chỉ cho phép nhân viên (Staff) truy cập.
     */
    public function index()
    {
        if (Auth::user()->position === 'Admin') {
            return redirect()->route('dashboard')->with('error', 'Admin vui lòng xem báo cáo, không trực tiếp bán hàng.');
        }

        $categories = Category::all();
        $products = Product::where('status', 'active')->with('category')->get();
        $tables = CoffeeTable::all();

        return view('pos.index', compact('categories', 'products', 'tables'));
    }

    /**
     * Lấy thông tin món khách đã đặt qua QR
     */
    public function getTableOrder($id)
    {
        try {
            $order = Order::where('table_id', $id)
                ->where('payment_status', 'unpaid')
                ->whereIn('status', ['pending', 'preparing'])
                ->with(['details.product'])
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Ban nay hien dang trong.']);
            }

            $details = $order->details->map(function ($item) {
                return [
                    'id'    => $item->product_id,
                    'name'  => $item->product->name,
                    'price' => (int)$item->price,
                    'qty'   => $item->quantity,
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
     * Xác nhận đơn hàng gửi xuống bếp
     */
    public function sendToKitchen(Request $request)
    {
        $request->validate(['order_id' => 'required|exists:orders,id']);

        try {
            DB::beginTransaction();
            $order = Order::findOrFail($request->order_id);
            $order->update(['status' => 'preparing']);
            CoffeeTable::where('id', $order->table_id)->update(['status' => 'occupied']);
            DB::commit();
            
            return response()->json(['success' => true, 'message' => 'Da gui don xuong bep!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * XỬ LÝ THANH TOÁN (QUAN TRỌNG NHẤT)
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'table_id'       => 'required|exists:coffee_tables,id',
            'cart'           => 'required|array|min:1',
            'payment_method' => 'required|in:cash,card,banking',
            'total_amount'   => 'required|numeric'
        ]);

        try {
            DB::beginTransaction();

            // 1. Kiểm tra đơn hàng cũ (QR)
            if ($request->filled('order_id')) {
                $order = Order::findOrFail($request->order_id);
                $order->update([
                    'user_id'        => Auth::id(),
                    'total_amount'   => $request->total_amount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'paid',
                    'status'         => 'completed', // Chuyển về completed để nhảy báo cáo
                ]);

                // ĐỒNG BỘ MÓN: Xóa chi tiết cũ và tạo lại theo giỏ hàng mới nhất trên POS
                OrderDetail::where('order_id', $order->id)->delete();
            } else {
                // 2. Tạo đơn mới nếu nhân viên nhập trực tiếp
                $order = Order::create([
                    'table_id'       => $request->table_id,
                    'user_id'        => Auth::id(),
                    'total_amount'   => $request->total_amount,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'paid',
                    'status'         => 'completed',
                    'order_date'     => now(),
                ]);
            }

            // 3. Lưu chi tiết món (Dùng chung cho cả 2 trường hợp)
            foreach ($request->cart as $item) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                ]);
            }

            // 4. Giải phóng bàn
            CoffeeTable::where('id', $request->table_id)->update(['status' => 'empty']);

            DB::commit();

            return response()->json([
                'success'  => true,
                'order_id' => $order->id,
                'message'  => 'Thanh toan thanh cong!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * In hóa đơn
     */
    public function printReceipt($id)
    {
        $order = Order::with(['details.product', 'table'])->findOrFail($id);
        return view('pos.receipt', compact('order'));
    }
}