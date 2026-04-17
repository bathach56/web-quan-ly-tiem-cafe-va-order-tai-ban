<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin()
    {
        // Nếu người dùng đã đăng nhập rồi thì tự động chuyển hướng về trang tương ứng
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Xử lý quá trình đăng nhập
     */
    public function processLogin(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // 2. Thử đăng nhập vào hệ thống
        // Lưu ý: Sử dụng 'username' thay vì 'email' theo cấu trúc DB của bạn
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->remember)) {
            
            // Tạo lại Session để bảo mật
            $request->session()->regenerate();

            // Lưu tên người dùng vào Session để hiển thị trên Topbar
            Session::put('user_name', Auth::user()->name);

            // 3. Điều hướng dựa trên chức vụ
            return $this->redirectByUserRole(Auth::user())
                ->with('success', 'Chào mừng ' . Auth::user()->name . ' quay trở lại!');
        }

        // 4. Trả về lỗi nếu đăng nhập thất bại
        return back()->withErrors([
            'username' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ])->onlyInput('username');
    }

    /**
     * Hàm phụ trợ để điều hướng người dùng dựa trên chức vụ
     */
    private function redirectByUserRole($user)
    {
        // Admin: Vào trang Tổng quan hệ thống
        if ($user->position === 'Admin') {
            return redirect()->route('dashboard');
        }

        // Nhân viên (Staff/Barista...): Vào thẳng máy bán hàng POS
        return redirect()->route('pos.index');
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hủy toàn bộ Session hiện tại
        $request->session()->invalidate();

        // Tạo lại Token CSRF mới
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}