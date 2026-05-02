<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Khai báo đầy đủ các Controller để hệ thống nhận diện
use App\Http\Controllers\{
    AuthController,
    DashboardController,
    ProductController,
    PosController,
    SettingController,
    ReportController,
    InventoryController,
    ProfileController,
    CategoryController,
    TableController,
    EmployeeController,
    CustomerController,
    CustomerOrderController,
    TableManagementController,
    ForgotPasswordController,
    ResetPasswordController,
    VoucherController
};

/*
|--------------------------------------------------------------------------
| Web Routes - HUTECH Coffee System (Nhóm 3 - 23DTHB6)
|--------------------------------------------------------------------------
*/

// =============================================================
// 1. DÀNH CHO KHÁCH HÀNG (PUBLIC)
// =============================================================

// Trang chủ giới thiệu (Hiển thị Hero, Best Seller)
Route::get('/', [CustomerController::class, 'index'])->name('customer.home');

// Hệ thống đặt món qua QR tại bàn (Khớp với link QR đã generate trong Admin)
Route::prefix('order')->name('customer.')->group(function () {
    Route::get('/table/{id}', [CustomerOrderController::class, 'index'])->name('menu');
    Route::post('/submit', [CustomerOrderController::class, 'storeOrder'])->name('order.submit');
});

// API kiểm tra Voucher nhanh cho Khách và Nhân viên
Route::post('/api/check-voucher', [CustomerOrderController::class, 'checkVoucher'])->name('api.check_voucher');


// =============================================================
// 2. XÁC THỰC (AUTH SYSTEM)
// =============================================================
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'processLogin')->name('login.process');
    
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'processRegister')->name('register.process');
    
    Route::match(['get', 'post'], '/logout', 'logout')->name('logout');
});

// Quên mật khẩu
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// =============================================================
// 3. KHU VỰC NỘI BỘ (YÊU CẦU ĐĂNG NHẬP)
// =============================================================
Route::middleware(['auth'])->group(function () {
    
    /**
     * ĐIỀU HƯỚNG THÔNG MINH:
     * Sau khi login, route này sẽ đá Admin sang Dashboard và Staff sang máy POS
     */
    Route::get('/admin-home', function () {
        return Auth::user()->position === 'Admin' 
            ? redirect()->route('dashboard') 
            : redirect()->route('pos.index');
    })->name('admin.redirect');

    // --- DASHBOARD QUẢN TRỊ (Dành cho Admin) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- MÁY BÁN HÀNG POS (Dành cho Nhân viên & Admin) ---
    Route::prefix('pos')->name('pos.')->controller(PosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/checkout', 'checkout')->name('checkout');
        Route::get('/table-order/{id}', 'getTableOrder')->name('table_order');
        Route::get('/print-receipt/{orderId}', 'printReceipt')->name('print_receipt');
        Route::post('/apply-voucher', 'applyVoucher')->name('apply_voucher');
    });

    // --- QUẢN LÝ BÀN & SƠ ĐỒ (QR CODE) ---
    Route::prefix('tables')->name('tables.')->controller(TableController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/bulk-destroy', 'bulkDestroy')->name('bulk_destroy');
        Route::delete('/{id}', 'destroy')->name('destroy');
        
        // Sơ đồ tầng & trạng thái thời gian thực
        Route::get('/floor-plan', 'floorPlan')->name('floor_plan');
        Route::get('/fetch-status', 'fetchStatus')->name('fetch_status'); 
    });

    // --- QUẢN LÝ TÀI NGUYÊN (CRUD) ---
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('vouchers', VoucherController::class);

    // --- QUẢN LÝ KHO HÀNG ---
    Route::prefix('inventory')->name('inventory.')->controller(InventoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update-stock', 'updateStock')->name('update_stock');
    });

    // --- BÁO CÁO & XUẤT DỮ LIỆU ---
    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export/excel', 'exportExcel')->name('excel');
        Route::get('/export/pdf', 'exportPDF')->name('pdf');
    });

    // --- CẤU HÌNH SHOP & HỒ SƠ CÁ NHÂN ---
    Route::controller(SettingController::class)->group(function () {
        Route::get('/settings', 'index')->name('settings.index');
        Route::post('/settings/update', 'update')->name('settings.update');
    });
    
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update', 'update')->name('profile.update');
    });
});