<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách tất cả danh mục
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Thêm danh mục mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status'      => 'nullable|in:active,inactive',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.max'      => 'Tên danh mục không được vượt quá 255 ký tự.',
        ]);

        Category::create([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status ?? 'active',
        ]);

        return back()->with('success', '✅ Thêm danh mục mới thành công!');
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status'      => 'required|in:active,inactive',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'status.required' => 'Vui lòng chọn trạng thái.',
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => $request->status,
        ]);

        return back()->with('success', '✅ Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Kiểm tra xem danh mục có đang chứa sản phẩm nào không
        if ($category->products()->count() > 0) {
            return back()->with('error', '❌ Không thể xóa danh mục này vì đang có món ăn thuộc danh mục!');
        }

        $category->delete();

        return back()->with('success', '✅ Đã xóa danh mục thành công!');
    }
}