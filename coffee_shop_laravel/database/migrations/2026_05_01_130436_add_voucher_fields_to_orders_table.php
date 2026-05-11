<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        // Thêm các cột còn thiếu để lưu thông tin giảm giá
        if (!Schema::hasColumn('orders', 'voucher_code')) {
            $table->string('voucher_code')->nullable()->after('status');
        }
        if (!Schema::hasColumn('orders', 'discount_amount')) {
            $table->decimal('discount_amount', 15, 2)->default(0)->after('voucher_code');
        }
        if (!Schema::hasColumn('orders', 'final_amount')) {
            $table->decimal('final_amount', 15, 2)->default(0)->after('discount_amount');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
