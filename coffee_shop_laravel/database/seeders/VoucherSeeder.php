<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Mã giảm giá theo phần trăm (Dành cho khai trương)
        Voucher::create([
            'code' => 'KHAITRUONG50',
            'name' => 'Mừng Khai Trương HUTECH Coffee',
            'type' => 'percentage',
            'discount_value' => 50,
            'min_order_value' => 0,
            'limit_uses' => 100,
            'used_count' => 0,
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
            'status' => 'active',
        ]);

        // 2. Mã giảm tiền mặt (Dành cho sinh viên HUTECH)
        Voucher::create([
            'code' => 'SINHVIENHUTECH',
            'name' => 'Ưu đãi đặc quyền Sinh Viên',
            'type' => 'fixed',
            'discount_value' => 20000,
            'min_order_value' => 50000, // Đơn từ 50k mới được dùng
            'limit_uses' => 500,
            'used_count' => 0,
            'start_date' => now(),
            'end_date' => now()->addYear(),
            'status' => 'active',
        ]);

        // 3. Mã giới hạn lượt dùng (Sắp hết lượt)
        Voucher::create([
            'code' => 'QUATANGHUYENBIE',
            'name' => 'Quà tặng giới hạn',
            'type' => 'fixed',
            'discount_value' => 15000,
            'min_order_value' => 30000,
            'limit_uses' => 10,
            'used_count' => 9, // Test trường hợp sắp hết lượt dùng
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(7),
            'status' => 'active',
        ]);

        // 4. Mã SẮP HẾT HẠN (Test logic tự động ẩn ở máy POS)
        // Mã này chỉ còn hiệu lực trong 2 giờ tới
        Voucher::create([
            'code' => 'GIAMGIACHOPNHOAN',
            'name' => 'Flash Sale Chớp Nhoáng',
            'type' => 'percentage',
            'discount_value' => 30,
            'min_order_value' => 0,
            'limit_uses' => null,
            'used_count' => 0,
            'start_date' => now()->subHour(1),
            'end_date' => now()->addHours(2), 
            'status' => 'active',
        ]);

        // 5. Mã TRONG TƯƠNG LAI (Không được hiện ở máy POS)
        Voucher::create([
            'code' => 'NAMMOI2027',
            'name' => 'Chào mừng năm mới 2027',
            'type' => 'fixed',
            'discount_value' => 50000,
            'min_order_value' => 200000,
            'limit_uses' => 100,
            'used_count' => 0,
            'start_date' => Carbon::create(2027, 1, 1, 0, 0, 0),
            'end_date' => Carbon::create(2027, 1, 5, 23, 59, 59),
            'status' => 'active',
        ]);
    }
}