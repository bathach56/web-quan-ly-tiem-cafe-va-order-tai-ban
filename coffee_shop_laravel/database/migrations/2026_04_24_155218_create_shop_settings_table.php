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
    Schema::create('shop_settings', function (Blueprint $table) {
        $table->id();
        $table->string('shop_name')->default('HUTECH Coffee');
        $table->string('address')->nullable();
        $table->string('phone')->nullable();
        $table->string('logo')->nullable();
        $table->timestamps();
    });

    // Chèn luôn 1 dòng dữ liệu mẫu để không bị lỗi null
    DB::table('shop_settings')->insert([
        'shop_name' => 'HUTECH Coffee',
        'address' => '475A Điện Biên Phủ, P.25, Bình Thạnh, TP.HCM',
        'phone' => '0123 456 789',
        'created_at' => now(),
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_settings');
    }
};
