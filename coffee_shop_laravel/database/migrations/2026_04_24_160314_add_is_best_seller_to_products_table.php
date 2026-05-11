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
    Schema::table('products', function (Blueprint $table) {
        // Thêm cột is_best_seller kiểu boolean, mặc định là 0 (không phải best seller)
        // Mình đặt nó sau cột price cho dễ quản lý nhé
        $table->boolean('is_best_seller')->default(false)->after('price');
    });
}

public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('is_best_seller');
    });
}
};
