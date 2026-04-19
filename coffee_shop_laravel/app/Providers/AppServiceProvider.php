<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Nhớ thêm dòng này
use Illuminate\Support\Facades\DB;   // Nhớ thêm dòng này

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void
    {
        // Đoạn code này giúp biến $shop_setting luôn có sẵn ở TẤT CẢ các file .blade.php
        View::composer('*', function ($view) {
            $setting = DB::table('settings')->first();
            $view->with('shop_setting', $setting);
        });
    }
}