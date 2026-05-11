<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoffeeTable;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerOrderController extends Controller
{
    /**
     * Hiển thị menu cho khách hàng qua mã QR
     */
    public function index($id)
    {
        $table = CoffeeTable::findOrFail($id);
        $shop_setting = DB::table('shop_settings')->first();
        
        $categories = Category::where('status', 'active')->get();
        $products = Product::where('status', 'active')->with('category')->get();

        // Lấy danh sách món bán chạy (Nếu có đánh dấu)
        $bestSellers = Product::where('status', 'active')
                                ->where('is_best_seller', 1) 
                                ->limit(4)
                                ->get();

        return view('customer.order', compact('table', 'categories', 'products', 'bestSellers', 'shop_setting'));
    }

    /**
     * AJAX: Kiểm tra mã Voucher từ phía khách hàng (Mobile)
     */
    public function checkVoucher(Request $request)
    {
        $now = Carbon::now();
        $voucher = Voucher::where('code', $request->code)
            ->where('status', 'active')
            ->where(function($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.']);
        }

        // Kiểm tra lượt sử dụng
        if ($voucher->limit_uses !== null && $voucher->used_count >= $voucher->limit_uses) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        return response()->json([
            'success' => true,
            'code' => $voucher->code,
            'type' => $voucher->type,
            'value' => (int)$voucher->discount_value,
            'min_order' => (int)$voucher->min_order_value,
            'message' => 'Áp dụng mã thành công!'
        ]);
    }

    /**
     * XỬ LÝ GỬI ĐƠN HÀNG: Hỗ trợ cộng dồn và lưu ghi chú
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:coffee_tables,id',
            'cart'     => 'required|array|min:1',
            'cart.*.id'    => 'required|exists:products,id',
            'cart.*.qty'   => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // 1. Kiểm tra xem bàn này đã có đơn hàng nào chưa thanh toán không
            $order = Order::where('table_id', $request->table_id)
                          ->where('payment_status', 'unpaid')
                          ->whereIn('status', ['pending', 'preparing', 'ordered', 'unconfirmed'])
                          ->first();

            // Tính tổng tiền của lượt đặt món này
            $current_subtotal = 0;
            foreach ($request->cart as $item) {
                $current_subtotal += $item['price'] * $item['qty'];
            }

            // 2. Tính toán giảm giá Voucher (nếu có)
            $discount_amount = 0;
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher && ($voucher->used_count < $voucher->limit_uses || is_null($voucher->limit_uses))) {
                    $discount_amount = ($voucher->type == 'percentage') 
                        ? ($current_subtotal * $voucher->discount_value) / 100 
                        : $voucher->discount_value;
                    
                    $voucher->increment('used_count'); // Đánh dấu đã dùng 1 lượt
                }
            }

            if ($order) {
                /**
                 * TRƯỜNG HỢP 1: Cộng dồn vào đơn cũ (Khách gọi thêm món)
                 */
                $order->total_amount += $current_subtotal;
                $order->discount_amount += $discount_amount;
                $order->final_amount = $order->total_amount - $order->discount_amount;
                
                // Nếu khách có ghi chú mới, nối thêm vào ghi chú cũ
                if ($request->note) {
                    $order->note = $order->note . " | Thêm: " . $request->note;
                }
                $order->save();

                foreach ($request->cart as $item) {
                    // Kiểm tra món này đã có trong đơn chi tiết chưa
                    $detail = OrderDetail::where('order_id', $order->id)
                                         ->where('product_id', $item['id'])
                                         ->first();
                    if ($detail) {
                        $detail->quantity += $item['qty'];
                        $detail->save();
                    } else {
                        OrderDetail::create([
                            'order_id'   => $order->id,
                            'product_id' => $item['id'],
                            'quantity'   => $item['qty'],
                            'price'      => $item['price'],
                        ]);
                    }
                }
            } else {
                /**
                 * TRƯỜNG HỢP 2: Tạo đơn mới hoàn toàn (Lần đầu đặt món)
                 */
                $order = Order::create([
                    'table_id'        => $request->table_id,
                    'total_amount'    => $current_subtotal,
                    'discount_amount' => $discount_amount,
                    'final_amount'    => max(0, $current_subtotal - $discount_amount),
                    'status'          => 'ordered', // Trạng thái chờ từ QR
                    'payment_status'  => 'unpaid',
                    'voucher_code'    => $request->voucher_code,
                    'note'            => $request->note ?? 'Khách đặt qua QR',
                    'order_date'      => Carbon::now(),
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

            // 3. Cập nhật trạng thái bàn sang 'occupied' (Đã có khách)
            // Đồng thời kích hoạt thông báo Badge đỏ bên máy POS
            CoffeeTable::where('id', $request->table_id)->update(['status' => 'occupied']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu gọi món đã được gửi! Vui lòng đợi trong giây lát.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
}