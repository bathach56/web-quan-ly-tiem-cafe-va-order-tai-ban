<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VoucherController extends Controller
{
    /**
     * Hiển thị trang Quản lý Voucher
     * Dòng 25 đã được sửa để trỏ thẳng vào file layouts/admin.blade.php theo yêu cầu của Thịnh
     */
    public function index()
    {
        // 1. Lấy dữ liệu thiết lập quán (Tên, Logo...) để hiển thị trên Topbar/Sidebar
        $shop_setting = DB::table('shop_settings')->first();

        // 2. Lấy danh sách toàn bộ voucher mới nhất
        $vouchers = Voucher::latest()->get();

        // 3. TRẢ VỀ VIEW: Trỏ thẳng vào layouts.admin
        // Lưu ý: Thịnh nhớ đặt vòng lặp @foreach($vouchers as $v) bên trong file layouts/admin.blade.php nhé
        return view('admin.vouchers', compact('vouchers', 'shop_setting'));
    }

    /**
     * Xử lý thêm mới Voucher từ Modal
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'code' => 'required|unique:vouchers,code|max:50',
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
        ], [
            'code.unique' => 'Mã giảm giá này đã tồn tại trong hệ thống!',
        ]);

        // Tạo Voucher mới
        Voucher::create([
            'code' => strtoupper($request->code), // Luôn viết hoa mã code
            'name' => $request->name,
            'type' => $request->type,
            'discount_value' => $request->discount_value,
            'min_order_value' => $request->min_order_value ?? 0,
            'limit_uses' => $request->limit_uses,
            'status' => $request->status ?? 'active',
            'used_count' => 0,
        ]);

        return back()->with('success', 'Đã thêm mã giảm giá mới thành công!');
    }

    /**
     * Cập nhật thông tin Voucher (Sửa)
     */
    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->validate([
            'code' => 'required|max:50|unique:vouchers,code,' . $id,
            'name' => 'required|string|max:255',
            'discount_value' => 'required|numeric|min:0',
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'status' => $request->status,
            // Thêm các trường khác nếu Thịnh muốn sửa thêm
        ]);

        return back()->with('success', 'Đã cập nhật thông tin Voucher!');
    }

    /**
     * Xóa Voucher khỏi hệ thống
     */
    public function destroy($id)
    {
        Voucher::destroy($id);
        return back()->with('success', 'Đã xóa mã giảm giá thành công.');
    }
}