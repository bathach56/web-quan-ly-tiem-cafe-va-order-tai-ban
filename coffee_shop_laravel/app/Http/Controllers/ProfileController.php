<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();

        // NẾU BẠN DÙNG SESSION ĐĂNG NHẬP (Chưa dùng Auth): 
        // Tạo một object tạm để View không bị lỗi
        if (!$user) {
            $user = (object)[
                'name' => session('user_name', 'Quản Trị Viên'),
                'email' => 'admin@premiumcoffee.com' // Email mặc định
            ];
        }

        return view('profile.index', compact('user'));
    }

    public function update(Request $request) {
        $userId = Auth::id(); 
        
        if ($userId) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }
        
        // Cập nhật lại Session tên hiển thị trên góc phải
        session(['user_name' => $request->name]);

        return back()->with('success', 'Cập nhật hồ sơ thành công!');
    }
}