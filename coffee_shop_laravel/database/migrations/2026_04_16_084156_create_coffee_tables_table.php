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
        Schema::create('coffee_tables', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Ví dụ: Bàn 01, Bàn 02
        $table->string('area'); // Ví dụ: Tầng 1, Sân vườn
        $table->string('status')->default('empty'); // empty, occupied, waiting
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coffee_tables');
    }
};
