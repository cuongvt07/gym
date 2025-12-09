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
        Schema::table('khach_hang', function (Blueprint $table) {
            $table->date('ngay_bat_dau')->nullable()->comment('Ngày bắt đầu hiệu lực thẻ');
            $table->integer('thoi_han_thang')->nullable()->comment('Thời hạn thẻ (3, 6, 12 tháng)');
            $table->date('ngay_het_han')->nullable()->comment('Ngày hết hạn thẻ');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khach_hang', function (Blueprint $table) {
            $table->dropColumn(['ngay_bat_dau', 'thoi_han_thang', 'ngay_het_han']);
        });
    }
};
