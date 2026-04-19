<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * 1. HIỂN THỊ DANH SÁCH MÓN ĂN
     */
    public function index()
    {
        // Lấy danh sách sản phẩm kèm thông tin danh mục, sắp xếp mới nhất lên đầu
        $products = Product::with('category')->orderBy('id', 'desc')->get();
        
        // Lấy tất cả danh mục để đổ vào các ô Select trong Modal Thêm/Sửa
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * 2. LƯU MÓN ĂN MỚI (TỪ MODAL THÊM)
     */
    public function store(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->status = 'active'; // Mặc định món mới là đang bán

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $fileName);
            $product->image = $fileName;
        } else {
            $product->image = 'default.jpg'; // Nếu không chọn ảnh, dùng ảnh mặc định
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Đã thêm món ăn mới thành công!');
    }

    /**
     * 3. CẬP NHẬT MÓN ĂN (TỪ MODAL SỬA)
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->status = $request->status;

        // Xử lý đổi hình ảnh
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ trong thư mục public/img nếu có (tránh rác máy chủ)
            if ($product->image != 'default.jpg') {
                $oldPath = public_path('img/' . $product->image);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Lưu ảnh mới
            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $fileName);
            $product->image = $fileName;
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Đã cập nhật thông tin món ăn!');
    }

    /**
     * 4. XÓA MÓN ĂN
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh của món đó trước khi xóa dữ liệu trong DB
        if ($product->image != 'default.jpg') {
            $imagePath = public_path('img/' . $product->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Đã xóa món ăn khỏi thực đơn!');
    }
}