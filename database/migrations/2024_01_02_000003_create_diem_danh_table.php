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
        Schema::create('diem_danh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lich_tap')->constrained('lich_tap')->onDelete('cascade');
            $table->foreignId('id_khach_hang')->constrained('khach_hang')->onDelete('cascade');
            $table->date('ngay_diem_danh');
            $table->time('gio_check_in');
            $table->time('gio_check_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diem_danh');
    }
};
