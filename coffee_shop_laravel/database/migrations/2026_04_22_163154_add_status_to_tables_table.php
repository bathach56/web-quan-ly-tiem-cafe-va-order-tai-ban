<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            // Thêm cột status để quản lý trạng thái bàn và hiển thị thông báo real-time
            // Giá trị mặc định là 'available' (Bàn trống)
            $table->string('status')->default('available')->after('name');
            
            /* Gợi ý các trạng thái cho Thịnh:
               - 'available': Bàn trống, sẵn sàng đón khách.
               - 'pending': Khách vừa gửi đơn từ QR, cần hiện CHẤM ĐỎ cho nhân viên.
               - 'occupied': Đã xác nhận đơn, khách đang dùng bữa tại bàn.
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            // Xóa cột status khi thực hiện lệnh rollback
            $table->dropColumn('status');
        });
    }
};