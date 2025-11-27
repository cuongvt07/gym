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
        Schema::create('lich_tap', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dang_ky_goi')->constrained('dang_ky_goi')->onDelete('cascade');
            $table->foreignId('id_pt')->constrained('pt')->onDelete('cascade');
            $table->foreignId('id_khach_hang')->constrained('khach_hang')->onDelete('cascade');
            $table->date('ngay_tap');
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->text('ghi_chu')->nullable();
            $table->enum('trang_thai', ['da_xep', 'da_hoc', 'huy', 'vang_mat'])->default('da_xep');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_tap');
    }
};
