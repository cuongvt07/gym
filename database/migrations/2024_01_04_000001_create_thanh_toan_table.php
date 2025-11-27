<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thanh_toan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dang_ky_goi')->constrained('dang_ky_goi')->onDelete('cascade');
            $table->decimal('so_tien', 12, 0); // VND
            $table->dateTime('ngay_thanh_toan');
            $table->enum('phuong_thuc', ['tien_mat'])->default('tien_mat');
            $table->foreignId('nguoi_thu')->constrained('nguoi_dung'); // Admin ID
            $table->string('ma_hoa_don')->unique(); // INV-YYYYMMDD-XXX
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thanh_toan');
    }
};
