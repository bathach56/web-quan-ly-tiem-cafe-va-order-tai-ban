<?php

namespace App\Http\Controllers;

use App\Models\CoffeeTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Hiển thị danh sách quản lý bàn (Dạng bảng)
     */
    public function index()
    {
        $tables = CoffeeTable::orderBy('name', 'asc')->get();
        return view('tables.index', compact('tables'));
    }

    /**
     * Hiển thị sơ đồ mặt bằng (Dạng Grid/React)
     */
    public function floorPlan()
    {
        $tables = CoffeeTable::orderBy('name', 'asc')->get();
        return view('tables.floor-plan', compact('tables'));
    }

    /**
     * API TRẢ VỀ JSON: Dùng cho React Polling cập nhật chấm đỏ mỗi 5 giây
     * Route: tables.fetch_status
     */
    public function fetchStatus()
    {
        $tables = CoffeeTable::orderBy('name', 'asc')->get();
        return response()->json($tables);
    }

    /**
     * Lưu bàn mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:50|unique:coffee_tables,name',
            'area'  => 'required|string|max:100',
            'status'=> 'nullable|in:available,occupied,pending',
        ], [
            'name.required' => 'Vui lòng nhập tên bàn.',
            'name.unique'   => 'Tên bàn này đã tồn tại.',
            'area.required' => 'Vui lòng chọn khu vực cho bàn.',
        ]);

        CoffeeTable::create([
            'name'   => $request->name,
            'area'   => $request->area,
            'status' => $request->status ?? 'available', // Mặc định là bàn trống
        ]);

        return back()->with('success', '✅ Đã thêm bàn mới thành công!');
    }

    /**
     * Cập nhật thông tin bàn
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:50|unique:coffee_tables,name,' . $id,
            'area'  => 'required|string|max:100',
            'status'=> 'required|in:available,occupied,pending',
        ]);

        $table = CoffeeTable::findOrFail($id);

        $table->update([
            'name'   => $request->name,
            'area'   => $request->area,
            'status' => $request->status,
        ]);

        return back()->with('success', '✅ Cập nhật thông tin bàn thành công!');
    }

    /**
     * Xóa một bàn
     */
    public function destroy($id)
    {
        $table = CoffeeTable::findOrFail($id);
        
        // Chặn xóa nếu bàn đang có khách hoặc đang có đơn chờ xác nhận
        if ($table->status !== 'available') {
            return back()->with('error', '❌ Không thể xóa bàn đang có khách hoặc đang chờ xử lý!');
        }

        $table->delete();

        return back()->with('success', '✅ Đã xóa bàn thành công!');
    }

    /**
     * Xóa nhiều bàn cùng lúc (Bulk Delete)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Không có bàn nào được chọn để xóa!');
        }

        // Chỉ cho phép xóa những bàn đang ở trạng thái 'available'
        $deletedCount = CoffeeTable::whereIn('id', $ids)
                                    ->where('status', 'available')
                                    ->delete();

        if ($deletedCount > 0) {
            return back()->with('success', "✅ Đã xóa thành công {$deletedCount} bàn trống!");
        }

        return back()->with('error', 'Không tìm thấy bàn trống nào để xóa.');
    }
}