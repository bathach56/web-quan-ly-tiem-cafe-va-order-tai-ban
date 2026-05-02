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
    Schema::table('shop_settings', function (Blueprint $table) {
        if (!Schema::hasColumn('shop_settings', 'email')) {
            $table->string('email')->nullable()->after('phone');
        }
        if (!Schema::hasColumn('shop_settings', 'working_hours')) {
            $table->string('working_hours')->nullable()->default('07:00 - 22:00')->after('email');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_settings', function (Blueprint $table) {
            //
        });
    }
};
