<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoffeeTable;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Voucher; // Nhớ import Model Voucher mới tạo
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

        $bestSellers = Product::where('status', 'active')
                                ->where('is_best_seller', 1) 
                                ->limit(4)
                                ->get();

        return view('customer.order', compact('table', 'categories', 'products', 'bestSellers', 'shop_setting'));
    }

    /**
     * AJAX: Kiểm tra mã Voucher từ phía khách hàng
     */
    public function checkVoucher(Request $request)
    {
        $voucher = Voucher::where('code', $request->code)
            ->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Mã không hợp lệ hoặc đã hết hạn.']);
        }

        // Kiểm tra xem đã hết lượt dùng chưa
        if ($voucher->limit_uses !== null && $voucher->used_count >= $voucher->limit_uses) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
        }

        return response()->json([
            'success' => true,
            'type' => $voucher->type,
            'value' => (int)$voucher->discount_value,
            'min_order' => (int)$voucher->min_order_value,
            'message' => 'Áp dụng mã thành công!'
        ]);
    }

    /**
     * Xử lý gửi đơn hàng (Hỗ trợ cộng dồn và áp dụng Voucher)
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

            // 1. Tìm đơn hiện tại của bàn
            $order = Order::where('table_id', $request->table_id)
                          ->where('payment_status', 'unpaid')
                          ->whereIn('status', ['pending', 'preparing', 'unconfirmed'])
                          ->first();

            $subtotal = 0;
            foreach ($request->cart as $item) {
                $subtotal += $item['price'] * $item['qty'];
            }

            // 2. Tính toán giảm giá Voucher (nếu khách có nhập mã)
            $discount_amount = 0;
            if ($request->voucher_code) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();
                if ($voucher) {
                    if ($voucher->type == 'percentage') {
                        $discount_amount = ($subtotal * $voucher->discount_value) / 100;
                    } else {
                        $discount_amount = $voucher->discount_value;
                    }
                    $voucher->increment('used_count');
                }
            }

            if ($order) {
                // CASE 1: Khách gọi thêm món vào đơn cũ
                $order->total_amount += $subtotal;
                $order->discount_amount += $discount_amount;
                // final_amount = tiền gốc - tiền giảm
                $order->final_amount = $order->total_amount - $order->discount_amount;
                $order->save();

                foreach ($request->cart as $item) {
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
                // CASE 2: Khách mở đơn mới hoàn toàn
                $order = Order::create([
                    'table_id'       => $request->table_id,
                    'total_amount'   => $subtotal,
                    'discount_amount'=> $discount_amount,
                    'final_amount'   => $subtotal - $discount_amount,
                    'status'         => 'pending', 
                    'payment_status' => 'unpaid',
                    'voucher_code'   => $request->voucher_code,
                    'note'           => $request->note ?? 'Khách đặt qua QR',
                    'order_date'     => Carbon::now(),
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

            // 3. Đổi trạng thái bàn sang 'pending' để báo hiệu cho nhân viên
            CoffeeTable::where('id', $request->table_id)->update(['status' => 'pending']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được gửi! Cảm ơn bạn.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}