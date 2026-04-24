<?php

namespace App\Http\Controllers; // Đã sửa đúng địa chỉ thư mục của Thịnh

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Hiển thị giao diện nhập mật khẩu mới
     */
    public function showResetForm($token)
    {
        // Trả về view reset-password đã thiết kế đồng bộ với giao diện Dark Theme
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Xử lý lưu mật khẩu mới vào Database
     */
    public function reset(Request $request)
    {
        // 1. Kiểm tra tính hợp lệ của dữ liệu đầu vào
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed', // password_confirmation phải khớp
        ], [
            'email.exists' => 'Email này không tồn tại trong hệ thống.',
            'password.confirmed' => 'Mật khẩu xác nhận không trùng khớp.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.'
        ]);

        // 2. Kiểm tra Token trong bảng chuẩn của Laravel 12
        // Nếu Thịnh chạy migration, bảng sẽ là 'password_reset_tokens'
        $record = DB::table('password_reset_tokens')
                    ->where([
                        'email' => $request->email,
                        'token' => $request->token,
                    ])
                    ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Mã xác thực (Token) không hợp lệ hoặc đã hết hạn.']);
        }

        // 3. Kiểm tra thời gian hết hạn (Token chỉ có hiệu lực trong 60 phút)
        $expires = Carbon::parse($record->created_at)->addMinutes(60);
        if (Carbon::now()->gt($expires)) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Yêu cầu đặt lại mật khẩu đã quá hạn. Vui lòng gửi lại.']);
        }

        // 4. Cập nhật mật khẩu mới cho nhân sự Nhóm 3
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // 5. Xóa Token sau khi đổi thành công để bảo mật
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // 6. Quay về trang Login với thông báo thành công rực rỡ
        return redirect()->route('login')->with('success', 'Mật khẩu đã được thay đổi! Hãy đăng nhập lại ca trực.');
    }
}