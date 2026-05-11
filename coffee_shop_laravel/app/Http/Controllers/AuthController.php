<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Hiển thị trang đăng nhập
     */
    public function showLogin()
    {
        // Nếu đã đăng nhập rồi thì không cho vào trang login nữa
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            
            // Lưu tên người dùng vào session để hiển thị trên Topbar
            Session::put('user_name', Auth::user()->name);

            return $this->redirectByUserRole(Auth::user())
                ->with('success', 'Chào mừng ' . Auth::user()->name . ' quay trở lại!');
        }

        return back()->withErrors([
            'username' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ])->onlyInput('username');
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByUserRole(Auth::user());
        }
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký tài khoản
     * Tự động gán quyền Admin cho người đăng ký đầu tiên của hệ thống
     */
    public function processRegister(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required'     => 'Vui lòng nhập họ tên.',
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.unique'   => 'Tên đăng nhập này đã có người sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed'=> 'Xác nhận mật khẩu không khớp.',
        ]);

        // Logic phân quyền tự động cho Nhóm 3
        $isFirstUser = User::count() === 0;
        $role = $isFirstUser ? 'Admin' : 'Staff';

        User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->username . '@hutechcoffee.local', // Tạo email giả định theo username
            'password' => Hash::make($request->password),
            'position' => $role,
            'status'   => 'active',
        ]);

        $message = $isFirstUser 
            ? 'Tài khoản Admin đầu tiên đã được khởi tạo!' 
            : 'Đăng ký tài khoản nhân viên thành công!';

        return redirect()->route('login')->with('success', $message . ' Vui lòng đăng nhập.');
    }

    /**
     * Điều hướng người dùng dựa trên chức vụ (Private Helper)
     */
    private function redirectByUserRole($user)
    {
        if ($user->position === 'Admin') {
            return redirect()->route('dashboard');
        }
        // Nhân viên (Staff) sẽ vào thẳng máy bán hàng POS
        return redirect()->route('pos.index');
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất khỏi hệ thống.');
    }
}