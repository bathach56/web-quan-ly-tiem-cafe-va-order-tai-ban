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

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->remember)) {
            $request->session()->regenerate();
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
        return view('auth.register');   // ← Thêm dòng này
    }

    /**
     * Xử lý đăng ký
     */
    /**
 /**
 * Xử lý đăng ký tài khoản
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
        'username.unique'   => 'Tên đăng nhập đã tồn tại.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
        'password.confirmed'=> 'Xác nhận mật khẩu không khớp.',
    ]);

    // LOGIC TỰ ĐỘNG: Nếu hệ thống chưa có người dùng nào, người này sẽ là Admin
    $isFirstUser = \App\Models\User::count() === 0;
    $role = $isFirstUser ? 'Admin' : 'Staff';

    \App\Models\User::create([
        'name'     => $request->name,
        'username' => $request->username,
        'email'    => $request->username . '@hutechcoffee.local',
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'position' => $role, // Gán quyền Admin nếu là người đầu tiên
        'status'   => 'active',
    ]);

    return redirect()->route('login')
        ->with('success', 'Đăng ký tài khoản ' . ($isFirstUser ? 'Admin' : 'nhân viên') . ' thành công! Vui lòng đăng nhập.');
}

    /**
     * Điều hướng theo vai trò
     */
    private function redirectByUserRole($user)
    {
        if ($user->position === 'Admin') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('pos.index');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}