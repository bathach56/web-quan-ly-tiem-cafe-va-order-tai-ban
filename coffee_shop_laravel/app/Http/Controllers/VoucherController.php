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
     */
    public function index()
    {
        // 1. Lấy dữ liệu thiết lập quán
        $shop_setting = DB::table('shop_settings')->first();

        // 2. Lấy danh sách toàn bộ voucher mới nhất
        $vouchers = Voucher::latest()->get();

        // 3. Trả về view quản lý voucher
        return view('admin.vouchers', compact('vouchers', 'shop_setting'));
    }

    /**
     * Xử lý thêm mới Voucher từ Modal
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu chặt chẽ
        $request->validate([
            'code' => 'required|unique:vouchers,code|max:50',
            'name' => 'required|string|max:255',
            'type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'code.unique' => 'Mã giảm giá này đã tồn tại!',
            'end_date.after_or_equal' => 'Ngày hết hạn không được trước ngày bắt đầu!',
        ]);

        // 2. Chuẩn bị dữ liệu để lưu
        $data = $request->all();
        $data['code'] = strtoupper($request->code);
        $data['used_count'] = 0;
        $data['status'] = $request->status ?? 'active';

        // 3. XỬ LÝ THỜI GIAN (Fix lỗi hiển thị sai & lỗi Vô hạn)
        // Dùng Carbon::parse để chuyển định dạng từ input datetime-local sang định dạng chuẩn Database
        if ($request->filled('start_date')) {
            $data['start_date'] = Carbon::parse($request->start_date);
        } else {
            $data['start_date'] = Carbon::now(); // Nếu để trống thì lấy thời điểm hiện tại
        }

        if ($request->filled('end_date')) {
            $data['end_date'] = Carbon::parse($request->end_date);
        } else {
            $data['end_date'] = null; // Nếu không nhập thì mới để Vô hạn
        }

        // 4. Lưu vào Database
        Voucher::create($data);

        return back()->with('success', 'Đã tạo mã voucher ' . $data['code'] . ' thành công!');
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($request->code);

        // Xử lý thời gian tương tự như hàm store
        if ($request->filled('start_date')) {
            $data['start_date'] = Carbon::parse($request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $data['end_date'] = Carbon::parse($request->end_date);
        } else {
            $data['end_date'] = null;
        }

        $voucher->update($data);

        return back()->with('success', 'Đã cập nhật thông tin mã ' . $voucher->code);
    }

    /**
     * Xóa Voucher
     */
    public function destroy($id)
    {
        Voucher::destroy($id);
        return back()->with('success', 'Đã xóa mã giảm giá khỏi hệ thống.');
    }
}