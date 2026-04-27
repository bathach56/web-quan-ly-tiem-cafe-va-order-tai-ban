<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\CoffeeTable;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PosController extends Controller
{
    /**
     * Hiển thị giao diện máy POS
     */
    public function index()
    {
        // 1. Lấy cấu hình quán (Dùng DB table để tránh lỗi Class ShopSetting not found)
        $shop_setting = DB::table('shop_settings')->first();

        // 2. Lấy dữ liệu thực đơn
        $categories = Category::where('status', 'active')->get();
        $products = Product::where('status', 'active')->with('category')->get();
        
        // 3. Lấy sơ đồ bàn
        $tables = CoffeeTable::orderBy('name', 'asc')->get();

        // 4. Lấy danh sách Voucher khả dụng (Đang chạy, trong hạn dùng, còn lượt)
        $now = Carbon::now();
        $vouchers = Voucher::where('status', 'active')
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->get()
            ->filter(function($v) {
                // Lọc thêm logic lượt dùng nếu có thiết lập limit_uses
                return is_null($v->limit_uses) || $v->used_count < $v->limit_uses;
            });

        return view('pos.index', compact('categories', 'products', 'tables', 'shop_setting', 'vouchers'));
    }

    /**
     * AJAX: Kiểm tra Voucher khi nhập tay hoặc chọn từ danh sách
     */
    public function applyVoucher(Request $request)
    {
        $now = Carbon::now();
        $voucher = Voucher::where('code', $request->code)
            ->where('status', 'active')
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.']);
        }

        // Kiểm tra thời hạn
        if (($voucher->start_date && $now->lt($voucher->start_date)) || 
            ($voucher->end_date && $now->gt($voucher->end_date))) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn sử dụng.']);
        }

        // Kiểm tra lượt dùng
        if (!is_null($voucher->limit_uses) && $voucher->used_count >= $voucher->limit_uses) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        // Kiểm tra đơn hàng tối thiểu
        if ($request->total < $voucher->min_order_value) {
            return response()->json([
                'success' => false, 
                'message' => 'Đơn hàng chưa đủ tối thiểu ' . number_format($voucher->min_order_value) . 'đ'
            ]);
        }

        return response()->json([
            'success' => true,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'value' => (int)$voucher->discount_value,
            'message' => 'Áp dụng thành công!'
        ]);
    }

    /**
     * Lấy thông tin đơn hàng hiện tại của bàn
     */
    public function getTableOrder($id)
    {
        try {
            $order = Order::where('table_id', $id)
                ->where('payment_status', 'unpaid')
                ->whereIn('status', ['pending', 'preparing'])
                ->with(['orderDetails.product']) 
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Bàn trống.']);
            }

            $details = $order->orderDetails->map(function ($item) {
                return [
                    'id'    => $item->product_id, 
                    'name'  => $item->product->name ?? 'Món đã xóa',
                    'price' => (int)$item->price,
                    'qty'   => $item->quantity,
                ];
            });

            return response()->json([
                'success'  => true,
                'order_id' => $order->id,
                'details'  => $details
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Lỗi tải dữ liệu.'], 500);
        }
    }

    /**
     * XỬ LÝ THANH TOÁN (Checkout)
     */
    public function checkout(Request $request) 
    {
        $request->validate([
            'table_id' => 'required',
            'cart' => 'required|array',
            'payment_method' => 'required'
        ]);

        DB::beginTransaction();
        try {
            // 1. Tạo hoặc cập nhật đơn hàng
            $order = $request->order_id ? Order::find($request->order_id) : new Order();
            
            $order->table_id = $request->table_id;
            $order->user_id = Auth::id(); // Nhân viên thu ngân
            $order->order_date = now();
            
            // 2. Tính toán tiền bạc
            $subtotal = 0;
            foreach ($request->cart as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }

            // Xử lý Voucher
            $voucher_discount = 0;
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher) {
                    $voucher_discount = ($voucher->type === 'percentage') 
                        ? ($subtotal * $voucher->discount_value / 100) 
                        : $voucher->discount_value;
                    
                    $order->voucher_code = $voucher->code;
                    $voucher->increment('used_count'); // Tăng lượt dùng voucher
                }
            }

            // Giảm giá nhập tay (%)
            $manual_percent = $request->manual_discount ?? 0;
            $manual_discount = ($subtotal * $manual_percent) / 100;

            $order->total_amount = $subtotal;
            $order->discount_amount = $voucher_discount + $manual_discount;
            $order->final_amount = max(0, $subtotal - $order->discount_amount);
            
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'paid';
            $order->status = 'completed';
            $order->save();

            // 3. Lưu chi tiết món ăn
            OrderDetail::where('order_id', $order->id)->delete();
            foreach ($request->cart as $item) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price']
                ]);
            }

            // 4. Cập nhật trạng thái bàn về Trống
            CoffeeTable::where('id', $request->table_id)->update(['status' => 'available']);

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500); 
        }
    }

    /**
     * In hóa đơn sau thanh toán
     */
    public function printReceipt($id)
    {
        $order = Order::with(['orderDetails.product', 'table', 'user'])->findOrFail($id);
        $shop_setting = DB::table('shop_settings')->first();
        return view('pos.receipt', compact('order', 'shop_setting'));
    }
}