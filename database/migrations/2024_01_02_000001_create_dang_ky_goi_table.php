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
        Schema::create('dang_ky_goi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_khach_hang')->constrained('khach_hang')->onDelete('cascade');
            $table->foreignId('id_goi_tap')->constrained('goi_tap')->onDelete('cascade');
            $table->foreignId('id_pt')->constrained('pt')->onDelete('cascade');
            $table->integer('tong_buoi');
            $table->integer('buoi_da_tap')->default(0);
            $table->integer('buoi_con_lai');
            $table->date('ngay_dang_ky');
            $table->enum('trang_thai', ['hoat_dong', 'het_han', 'huy'])->default('hoat_dong');
            $table->enum('trang_thai_thanh_toan', ['chua_thanh_toan', 'da_thanh_toan'])->default('chua_thanh_toan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dang_ky_goi');
    }
};
