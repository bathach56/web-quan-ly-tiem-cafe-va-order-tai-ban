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
    Schema::create('ingredients', function (Blueprint $table) {
        $table->id();
        $table->string('code')->unique(); // NL-001
        $table->string('name');           // Cà phê hạt...
        $table->string('unit');           // Kg, Lít, Thùng
        $table->integer('stock')->default(0); // Tồn kho hiện tại
        $table->integer('min_stock')->default(5); // Mức cảnh báo tồn tối thiểu
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
