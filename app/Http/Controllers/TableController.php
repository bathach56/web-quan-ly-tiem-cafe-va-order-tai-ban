<?php

namespace App\Http\Controllers;

use App\Models\CoffeeTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Hiển thị danh sách tất cả bàn
     */
    // Thêm hoặc thay thế hàm index thành 2 hàm rõ ràng
public function index()
{
    $tables = CoffeeTable::orderBy('name', 'asc')->get();
    return view('tables.index', compact('tables'));
}

// Thêm hàm mới này
public function floorPlan()
{
    $tables = CoffeeTable::orderBy('name', 'asc')->get();
    return view('tables.floor-plan', compact('tables'));
}

    /**
     * Lưu bàn mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:50|unique:coffee_tables,name',
            'area'  => 'required|string|max:100',
            'status'=> 'nullable|in:empty,occupied,waiting',
        ], [
            'name.required' => 'Vui lòng nhập tên bàn.',
            'name.unique'   => 'Tên bàn này đã tồn tại.',
            'area.required' => 'Vui lòng chọn khu vực cho bàn.',
        ]);

        CoffeeTable::create([
            'name'   => $request->name,
            'area'   => $request->area,
            'status' => $request->status ?? 'empty',
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
            'status'=> 'required|in:empty,occupied,waiting',
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
        
        // Có thể thêm kiểm tra: nếu bàn đang có khách thì không cho xóa
        // if ($table->status === 'occupied') {
        //     return back()->with('error', 'Không thể xóa bàn đang có khách!');
        // }

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

        // Xóa các bàn được chọn
        $deletedCount = CoffeeTable::whereIn('id', $ids)->delete();

        if ($deletedCount > 0) {
            return back()->with('success', "✅ Đã xóa thành công {$deletedCount} bàn!");
        }

        return back()->with('error', 'Không tìm thấy bàn nào để xóa.');
    }
}