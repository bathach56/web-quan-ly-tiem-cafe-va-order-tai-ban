<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Khai báo đầy đủ các Controller đã xây dựng
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
    CustomerOrderController
};

/*
|--------------------------------------------------------------------------
| Web Routes - Hệ thống Quản lý Coffee Shop (Hutech Coffee)
|--------------------------------------------------------------------------
*/

// 1. ĐIỀU HƯỚNG GỐC: Luôn ép vào trang Đăng nhập hoặc Điều hướng theo quyền
Route::get('/', function () {
    if (Auth::check()) {
        // Fix lỗi F5: Redirect thông minh dựa trên chức vụ
        return Auth::user()->position === 'Admin' 
            ? redirect()->route('dashboard') 
            : redirect()->route('pos.index');
    }
    return redirect()->route('login');
});

// 2. XÁC THỰC (PUBLIC ROUTES)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. DÀNH CHO KHÁCH HÀNG (QUÉT QR TẠI BÀN - KHÔNG CẦN ĐĂNG NHẬP)
Route::prefix('menu')->name('customer.')->group(function () {
    Route::get('/table/{id}', [CustomerOrderController::class, 'index'])->name('menu');
    Route::post('/order', [CustomerOrderController::class, 'storeOrder'])->name('order.submit');
});

// 4. KHU VỰC BẢO MẬT (YÊU CẦU ĐĂNG NHẬP)
Route::middleware(['auth'])->group(function () {
    
    // --- MÁY BÁN HÀNG POS (Dành cho Nhân viên) ---
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
        
        // AJAX: Nạp món từ QR vào khung POS
        Route::get('/table-order/{id}', [PosController::class, 'getTableOrder'])->name('table_order');
        
        // AJAX: Xác nhận gửi đơn xuống bếp
        Route::post('/send-kitchen', [PosController::class, 'sendToKitchen'])->name('send_kitchen');
        
        // In hóa đơn nhiệt (Receipt)
        Route::get('/print-receipt/{orderId}', [PosController::class, 'printReceipt'])->name('print_receipt');
    });

    // --- QUẢN TRỊ (Dành cho Admin) ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý Bàn & Sơ đồ mặt bằng
    Route::prefix('tables')->name('tables.')->group(function () {
        Route::get('/floor-plan', [TableController::class, 'floorPlan'])->name('floor_plan');
        Route::delete('/bulk-destroy', [TableController::class, 'bulkDestroy'])->name('bulk_destroy');
        
        // CRUD Bàn cơ bản
        Route::get('/', [TableController::class, 'index'])->name('index');
        Route::post('/store', [TableController::class, 'store'])->name('store');
        Route::put('/{id}', [TableController::class, 'update'])->name('update');
        Route::delete('/{id}', [TableController::class, 'destroy'])->name('destroy');
    });

    // Quản lý Thực đơn & Danh mục (Sử dụng Resource Route)
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    // Quản lý Nhân sự
    Route::resource('employees', EmployeeController::class);

    // Kho hàng
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::post('/store', [InventoryController::class, 'store'])->name('store');
        Route::post('/update-stock', [InventoryController::class, 'updateStock'])->name('update_stock');
    });

    // Báo cáo & Thống kê
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Cấu hình cửa hàng
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/update', [SettingController::class, 'update'])->name('update');
    });

    // Hồ sơ cá nhân
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update', [ProfileController::class, 'update'])->name('update');
    });
});