<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Tìm nhân viên trong hệ thống theo email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Cập nhật thông tin OAuth nếu chưa có
                if (!$user->provider_id) {
                    $user->update([
                        'provider' => 'google',
                        'provider_id' => $googleUser->getId(),
                        'avatar' => $user->avatar === 'default.jpg' ? $googleUser->getAvatar() : $user->avatar
                    ]);
                }

                // Nếu tài khoản bị khóa
                if ($user->status === 'inactive') {
                    return redirect()->route('login')->withErrors(['oauth' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ Quản lý.']);
                }

                // Đăng nhập
                Auth::login($user, true);

                return redirect()->intended('/dashboard')->with('success', 'Đăng nhập thành công!');
            } else {
                // Email chưa được Quản lý tạo trong hệ thống
                return redirect()->route('login')->withErrors(['oauth' => 'Tài khoản Google này chưa được liên kết với nhân sự nào. Vui lòng báo Quản lý thêm Email của bạn vào hệ thống trước!']);
            }

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['oauth' => 'Đã có lỗi xảy ra khi đăng nhập bằng Google. Vui lòng thử lại sau.']);
        }
    }
}
