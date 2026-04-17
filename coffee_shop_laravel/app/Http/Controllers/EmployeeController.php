<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File; // Bổ sung thư viện File để xóa ảnh rác

class EmployeeController extends Controller
{
    // 1. HIỂN THỊ DANH SÁCH
    public function index() {
        // Lấy danh sách nhân viên, nhân viên mới thêm sẽ nằm lên đầu tiên (desc)
        $employees = User::orderBy('id', 'desc')->get();

        return view('employees.index', compact('employees'));
    }

    // 2. THÊM NHÂN VIÊN MỚI
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Tối đa 2MB
        ]);

        $filename = null;

        // Xử lý upload ảnh thẻ ngay lúc thêm mới
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // Đặt tên file ngẫu nhiên: nv_thoigian_ngaunhien.jpg
            $filename = 'nv_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
        }

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->username . '@hutechcoffee.local', // Email ảo lách luật Database
            'password' => Hash::make($request->password), // Dùng Hash::make chuẩn Laravel
            'position' => $request->position,
            'status' => $request->status,
            'avatar' => $filename, // Lưu tên file vào CSDL
        ]);

        return back()->with('success', 'Thêm nhân viên thành công!');
    }

    // 3. CẬP NHẬT THÔNG TIN
    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->except(['password', 'avatar']);

        // Nếu nhập pass mới thì mới mã hóa và lưu
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Xử lý Upload ảnh khi Sửa
        if ($request->hasFile('avatar')) {
            
            // XÓA ẢNH CŨ: Giúp server không bị đầy rác
            if ($user->avatar && File::exists(public_path('uploads/avatars/' . $user->avatar))) {
                File::delete(public_path('uploads/avatars/' . $user->avatar));
            }

            $file = $request->file('avatar');
            $filename = 'nv_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            
            $data['avatar'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật nhân viên thành công!');
    }

    // 4. XÓA NHÂN VIÊN
    public function destroy($id) {
        $user = User::findOrFail($id);
        
        // Cực kỳ quan trọng: Xóa luôn ảnh thẻ của họ trong thư mục để dọn dẹp
        if ($user->avatar && File::exists(public_path('uploads/avatars/' . $user->avatar))) {
            File::delete(public_path('uploads/avatars/' . $user->avatar));
        }

        $user->delete();
        
        return back()->with('success', 'Đã xóa nhân viên và dọn dẹp dữ liệu!');
    }
}