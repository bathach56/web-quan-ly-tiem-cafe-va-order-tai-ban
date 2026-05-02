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
     * Hiển thị giao diện máy POS (HUTECH Coffee)
     */
    public function index()
    {
        // 1. Lấy cấu hình quán
        $shop_setting = DB::table('shop_settings')->first();

        // 2. Lấy thực đơn (Chỉ lấy món đang kinh doanh)
        $categories = Category::where('status', 'active')->get();
        $products = Product::where('status', 'active')->with('category')->get();
        
        // 3. Lấy sơ đồ bàn
        $tables = CoffeeTable::orderBy('name', 'asc')->get();

        // 4. Lấy danh sách Voucher khả dụng
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
                return is_null($v->limit_uses) || $v->used_count < $v->limit_uses;
            });

        return view('pos.index', compact('categories', 'products', 'tables', 'shop_setting', 'vouchers'));
    }

    /**
     * AJAX: Lấy đơn hàng từ bàn (Đồng bộ với khách đặt qua QR điện thoại)
     */
    public function getTableOrder($id)
    {
        // Nếu là chế độ mang về, không cần lấy đơn cũ
        if ($id === 'takeaway') {
            return response()->json(['success' => false]);
        }

        try {
            $order = Order::where('table_id', $id)
                ->where('payment_status', 'unpaid')
                ->whereIn('status', ['pending', 'preparing', 'ordered', 'unconfirmed'])
                ->with(['orderDetails.product']) 
                ->latest()
                ->first();

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Bàn trống.']);
            }

            // Chuyển đổi dữ liệu sang mảng 'cart' để nạp vào giao diện POS
            $cartData = $order->orderDetails->map(function ($item) {
                return [
                    'id'    => $item->product_id, 
                    'name'  => $item->product->name ?? 'Sản phẩm đã xóa',
                    'price' => (int)$item->price,
                    'qty'   => (int)$item->quantity,
                ];
            });

            return response()->json([
                'success'  => true,
                'order_id' => $order->id,
                'note'     => $order->note, // Trả về ghi chú khách đặt qua điện thoại
                'cart'     => $cartData 
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * XỬ LÝ THANH TOÁN (Checkout)
     */
    public function checkout(Request $request) 
    {
        // 1. Kiểm tra dữ liệu (table_id có thể là 'takeaway' hoặc ID số)
        if (!$request->cart || count($request->cart) == 0) {
            return response()->json(['success' => false, 'message' => 'Giỏ hàng trống']);
        }

        DB::beginTransaction();
        try {
            // 2. Xác định đơn hàng (Cập nhật đơn QR cũ hoặc tạo mới hoàn toàn)
            $order = null;
            if ($request->table_id !== 'takeaway') {
                $order = Order::where('table_id', $request->table_id)
                              ->where('payment_status', 'unpaid')
                              ->first();
            }
            
            if (!$order) {
                $order = new Order();
                $order->table_id = ($request->table_id === 'takeaway') ? null : $request->table_id;
            }
            
            $order->user_id = Auth::id(); // Nhân viên thực hiện thanh toán
            $order->order_date = now();
            
            // Xử lý Ghi chú (Note)
            $prefix = ($request->table_id === 'takeaway') ? "[MANG VỀ] " : "";
            $order->note = $prefix . $request->note;
            
            // 3. Tính toán tài chính
            $subtotal = 0;
            foreach ($request->cart as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }

            // Voucher giảm giá
            $voucher_discount = 0;
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher) {
                    $voucher_discount = ($voucher->type === 'percentage') 
                        ? ($subtotal * $voucher->discount_value / 100) 
                        : $voucher->discount_value;
                    
                    $order->voucher_code = $voucher->code;
                    $voucher->increment('used_count');
                }
            }

            // Giảm giá thủ công (%) từ máy POS
            $manual_percent = (float)($request->manual_discount ?? 0);
            $manual_discount = ($subtotal * $manual_percent) / 100;

            $order->total_amount = $subtotal;
            $order->discount_amount = $voucher_discount + $manual_discount;
            $order->final_amount = max(0, $subtotal - $order->discount_amount);
            
            $order->payment_method = $request->payment_method ?? 'cash';
            $order->payment_status = 'paid';
            $order->status = 'completed'; 
            $order->save();

            // 4. Đồng bộ chi tiết món ăn (Xóa cũ nạp mới từ POS)
            OrderDetail::where('order_id', $order->id)->delete();
            foreach ($request->cart as $item) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price']
                ]);
            }

            // 5. Giải phóng bàn nếu không phải khách mang về
            if ($request->table_id !== 'takeaway') {
                CoffeeTable::where('id', $request->table_id)->update(['status' => 'available']);
            }

            DB::commit();
            return response()->json(['success' => true, 'order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * In hóa đơn (Receipt)
     */
    public function printReceipt($id)
    {
        $order = Order::with(['orderDetails.product', 'table', 'user'])->findOrFail($id);
        $shop_setting = DB::table('shop_settings')->first();
        
        return view('pos.receipt', compact('order', 'shop_setting'));
    }
}