<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;

class ForgotPasswordController extends Controller
{
    // Hiển thị form nhập email
    public function showLinkRequestForm() {
        return view('auth.forgot-password');
    }

    // Xử lý gửi link reset
    public function sendResetLinkEmail(Request $request) {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // 1. Tạo Token duy nhất
        $token = Str::random(64);

        // 2. Lưu vào bảng password_resets
        DB::table('password_reset_tokens')->insert([ 
        'email' => $request->email,
        'token' => $token,
        'created_at' => Carbon::now()
]);

        // 3. Gửi Mail (Bạn có thể tạo một Mailable riêng)
        Mail::send('auth.email-template', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Đặt lại mật khẩu - HUTECH COFFEE');
        });

        return back()->with('success', 'Chúng tôi đã gửi link đặt lại mật khẩu vào email của bạn!');
    }
}