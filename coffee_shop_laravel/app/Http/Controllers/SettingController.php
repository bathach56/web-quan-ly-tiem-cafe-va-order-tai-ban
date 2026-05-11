<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index() {
        // Lấy bản ghi đầu tiên, nếu chưa có thì tạo mới
        $setting = DB::table('settings')->first();
        if (!$setting) {
            DB::table('settings')->insert(['shop_name' => 'Coffee Shop Dĩ An']);
            $setting = DB::table('settings')->first();
        }
        return view('settings.index', compact('setting'));
    }

    public function update(Request $request) {
        $data = [
            'shop_name'   => $request->shop_name,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'vat'         => $request->vat,
            'footer_text' => $request->footer_text,
        ];

        // Xử lý Upload Logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img'), $fileName);
            $data['logo'] = $fileName;
        }

        DB::table('settings')->where('id', 1)->update($data);

        return back()->with('success', 'Cập nhật cấu hình hệ thống thành công!');
    }
}