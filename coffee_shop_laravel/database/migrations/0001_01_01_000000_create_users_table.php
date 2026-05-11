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
    // 1. Bảng Người dùng (Users)
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        
        // Cột dùng để đăng nhập thay cho Email
        $table->string('username')->unique(); 

        // Cho phép email được trống để không bị bắt lỗi SQL
        $table->string('email')->nullable()->unique();
        
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        
        // Các cột mở rộng cho quản lý Coffee Shop
        $table->string('position')->nullable(); // Admin, Staff, Barista...
        $table->string('avatar')->nullable();   // Lưu tên file ảnh đại diện
        
        $table->rememberToken();
        $table->timestamps();
    });

    // 2. Bảng Token đặt lại mật khẩu
    Schema::create('password_reset_tokens', function (Blueprint $table) {
        $table->string('email')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });

    // 3. Bảng quản lý Phiên làm việc (Sessions)
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->foreignId('user_id')->nullable()->index();
        $table->string('ip_address', 45)->nullable();
        $table->text('user_agent')->nullable();
        $table->longText('payload');
        $table->integer('last_activity')->index();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
