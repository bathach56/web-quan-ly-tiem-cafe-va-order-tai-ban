<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Khai báo đầy đủ các Controller (Đã cập nhật đúng địa chỉ thư mục của Thịnh)
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
    CustomerOrderController,
    ForgotPasswordController,
    ResetPasswordController
};

/*
|--------------------------------------------------------------------------
| Web Routes - Hệ thống Quản lý Coffee Shop (Hutech Coffee)
|--------------------------------------------------------------------------
*/

// 1. ĐIỀU HƯỚNG GỐC: Điều hướng thông minh dựa trên chức vụ khi vào trang chủ
Route::get('/', function () {
    if (Auth::check()) {
        // Phân quyền: Admin vào Dashboard, nhân viên vào thẳng máy bán hàng POS
        return Auth::user()->position === 'Admin' 
            ? redirect()->route('dashboard') 
            : redirect()->route('pos.index');
    }
    return redirect()->route('login');
});

// 2. XÁC THỰC & QUÊN MẬT KHẨU (PUBLIC ROUTES)
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'processLogin')->name('login.process');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'processRegister')->name('register.process');
    Route::match(['get', 'post'], '/logout', 'logout')->name('logout');
});

// Chức năng Quên mật khẩu (Cấu hình qua Mailtrap đã xong)
Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// 3. DÀNH CHO KHÁCH HÀNG (QUÉT QR TẠI BÀN - KHÔNG CẦN ĐĂNG NHẬP)
Route::prefix('menu')->name('customer.')->controller(CustomerOrderController::class)->group(function () {
    Route::get('/table/{id}', 'index')->name('menu');
    Route::post('/order', 'storeOrder')->name('order.submit');
});

// 4. KHU VỰC BẢO MẬT (YÊU CẦU ĐĂNG NHẬP)
Route::middleware(['auth'])->group(function () {
    
    // --- TRANG CHỦ QUẢN TRỊ (Dành cho Admin) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- MÁY BÁN HÀNG POS (Nghiệp vụ chính của nhân viên) ---
    Route::prefix('pos')->name('pos.')->controller(PosController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/checkout', 'checkout')->name('checkout');
        
        // AJAX: Nạp món từ QR vào khung POS khi khách gọi món tại bàn
        Route::get('/table-order/{id}', 'getTableOrder')->name('table_order');
        
        // AJAX: Xác nhận gửi đơn xuống bếp để bắt đầu pha chế
        Route::post('/send-kitchen', 'sendToKitchen')->name('send_kitchen');
        
        // In hóa đơn (Receipt) - Mở tab mới để in
        Route::get('/print-receipt/{orderId}', 'printReceipt')->name('print_receipt');
    });

    // --- QUẢN LÝ BÀN & SƠ ĐỒ MẶT BẰNG ---
    Route::prefix('tables')->name('tables.')->controller(TableController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/floor-plan', 'floorPlan')->name('floor_plan');
        
        // ROUTE QUAN TRỌNG: Trả về JSON để React Polling cập nhật CHẤM ĐỎ real-time
        Route::get('/fetch-status', 'fetchStatus')->name('fetch_status'); 
        
        Route::post('/store', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/bulk-destroy', 'bulkDestroy')->name('bulk_destroy');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    Route::prefix('table-manage')->group(function () {
    Route::post('/transfer', [TableManagementController::class, 'transferTable'])->name('table.transfer');
    Route::post('/merge', [TableManagementController::class, 'mergeTable'])->name('table.merge');
    Route::post('/split', [TableManagementController::class, 'splitTable'])->name('table.split');
});

    // --- QUẢN LÝ THỰC ĐƠN & DANH MỤC (Resource Routes) ---
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    // --- QUẢN LÝ NHÂN SỰ (Nhóm 3) ---
    Route::resource('employees', EmployeeController::class);

    // --- QUẢN LÝ KHO HÀNG ---
    Route::prefix('inventory')->name('inventory.')->controller(InventoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update-stock', 'updateStock')->name('update_stock');
    });

// --- BÁO CÁO & THỐNG KÊ ---
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');  
    Route::get('/reports/excel', [ReportController::class, 'exportExcel'])->name('reports.excel');
    Route::get('/reports/pdf', [ReportController::class, 'exportPDF'])->name('reports.pdf');

    // --- CẤU HÌNH HỆ THỐNG ---
    Route::prefix('settings')->name('settings.')->controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });

    // --- HỒ SƠ CÁ NHÂN ---
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/update', 'update')->name('update');
    });
});