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
        // Kiểm tra đúng tên bảng là 'coffee_tables'
        if (Schema::hasTable('coffee_tables')) {
            Schema::table('coffee_tables', function (Blueprint $table) {
                // Kiểm tra nếu chưa có cột status thì mới thêm vào
                if (!Schema::hasColumn('coffee_tables', 'status')) {
                    $table->string('status')->default('available');
                    
                    /* Logic trạng thái của Nhóm 3:
                       - 'available': Bàn trống.
                       - 'pending': Khách vừa quét QR gọi món (hiện chấm đỏ).
                       - 'occupied': Khách đang ngồi và đã xác nhận đơn.
                    */
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('coffee_tables')) {
            Schema::table('coffee_tables', function (Blueprint $table) {
                if (Schema::hasColumn('coffee_tables', 'status')) {
                    $table->dropColumn('status');
                }
            });
        }
    }
};