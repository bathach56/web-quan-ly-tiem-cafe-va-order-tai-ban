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
    Schema::create('vouchers', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->string('name');
    $table->enum('type', ['percentage', 'fixed']);
    $table->decimal('discount_value', 10, 2);
    $table->decimal('min_order_value', 10, 2)->default(0);
    $table->integer('limit_uses')->nullable();
    $table->integer('used_count')->default(0);
    
    // Hai trường quan trọng Thịnh cần đây:
    $table->dateTime('start_date')->nullable();
    $table->dateTime('end_date')->nullable();
    
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
