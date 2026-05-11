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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // 1. Liên kết với bàn (Khóa ngoại)
            $table->unsignedBigInteger('table_id');
            
            // 2. Liên kết với nhân viên (nullable vì khách tự đặt QR thì chưa có nhân viên)
            $table->unsignedBigInteger('user_id')->nullable(); 
            
            // 3. Thông tin tài chính (dùng decimal cho chính xác tiền tệ)
            $table->decimal('total_amount', 15, 2)->default(0);
            
            // 4. Trạng thái đơn hàng và thanh toán
            $table->string('status')->default('pending');        // pending, completed, cancelled
            $table->string('payment_status')->default('unpaid'); // paid, unpaid
            $table->string('payment_method')->nullable();        // cash, card, banking
            
            // 5. Thông tin bổ sung
            $table->text('note')->nullable(); // Ghi chú của khách (ví dụ: ít đường, nhiều đá)
            $table->timestamp('order_date')->useCurrent();
            
            $table->timestamps();

            // Ràng buộc khóa ngoại
            // Lưu ý: Đảm bảo bảng bàn của Thịnh tên là 'coffee_tables' 
            $table->foreign('table_id')->references('id')->on('coffee_tables')->onDelete('cascade');
            
            // Khóa ngoại liên kết với bảng users (nhân viên)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};