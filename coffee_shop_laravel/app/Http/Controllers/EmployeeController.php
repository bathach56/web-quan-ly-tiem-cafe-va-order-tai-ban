<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class EmployeeController extends Controller
{
    /**
     * 1. HIỂN THỊ DANH SÁCH NHÂN VIÊN
     */
    public function index() 
{
    // Lọc đúng những người có position là 'staff'
    $employees = User::where('position', 'staff')
                    ->orderBy('id', 'desc')
                    ->get();

    return view('employees.index', compact('employees'));
}

    /**
     * 2. THÊM NHÂN VIÊN MỚI
     */
    public function store(Request $request) 
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|min:6',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Giới hạn 2MB
            'position' => 'required',
            'status'   => 'required'
        ]);

        $filename = null;

        // Xử lý upload ảnh nếu có
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // Đặt tên: nv_timestamp_random.jpg
            $filename = 'nv_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            
            // Đảm bảo thư mục tồn tại trước khi lưu
            $path = public_path('uploads/avatars');
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            
            $file->move($path, $filename);
        }

        // Tạo bản ghi mới
        User::create([
        'name'     => $request->name,
        'username' => $request->username,
        'email'    => $request->username . '@hutechcoffee.local',
        'password' => Hash::make($request->password),
        'position' => $request->position,
        'status'   => $request->status,
        'avatar'   => $filename,
    ]);

    return back()->with('success', 'Thêm nhân viên mới thành công!');
    }

    /**
     * 3. CẬP NHẬT THÔNG TIN NHÂN VIÊN
     */
    public function update(Request $request, $id) 
    {
        $user = User::findOrFail($id);

        // Xác thực dữ liệu (Bỏ qua unique cho chính ID hiện tại)
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // Chuẩn bị dữ liệu cập nhật (Loại bỏ pass và avatar để xử lý riêng)
        $data = $request->only(['name', 'username', 'position', 'status']);

        // Nếu có nhập mật khẩu mới thì mới cập nhật
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Xử lý thay đổi ảnh đại diện
        if ($request->hasFile('avatar')) {
            // XÓA ẢNH CŨ (Nếu có) để sạch bộ nhớ server
            if ($user->avatar) {
                $oldPath = public_path('uploads/avatars/' . $user->avatar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Lưu ảnh mới
            $file = $request->file('avatar');
            $filename = 'nv_edit_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            
            $data['avatar'] = $filename;
        }

        $user->update($data);

        return back()->with('success', 'Cập nhật thông tin nhân viên thành công!');
    }

    /**
     * 4. XÓA NHÂN VIÊN
     */
    public function destroy($id) 
    {
        $user = User::findOrFail($id);

        // Chặn không cho Admin tự xóa chính mình (Tránh lỗi hệ thống)
        if (auth()->id() == $id) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
        }

        // Xóa ảnh vật lý trong thư mục uploads
        if ($user->avatar) {
            $filePath = public_path('uploads/avatars/' . $user->avatar);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $user->delete();

        return back()->with('success', 'Đã xóa nhân viên và dọn dẹp dữ liệu ảnh thành công!');
    }
}