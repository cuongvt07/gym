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
        Schema::create('pt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nguoi_dung')->constrained('nguoi_dung')->onDelete('cascade');
            $table->decimal('luong_co_ban', 15, 2)->default(0);
            $table->decimal('luong_gio', 15, 2)->default(0);
            $table->integer('so_gio_tieu_chuan')->default(0);
            $table->text('kinh_nghiem')->nullable();
            $table->text('chung_chi')->nullable();
            $table->string('chuyen_mon')->nullable(); // JSON: Yoga, Gym, Boxing, etc.
            $table->enum('trang_thai', ['hoat_dong', 'nghi_viec', 'khoa'])->default('hoat_dong');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt');
    }
};
