<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giao_an_tap', function (Blueprint $table) {
            $table->id();
            $table->string('ten_giao_an');
            $table->text('mo_ta')->nullable();
            $table->enum('muc_tieu', ['tang_co', 'giam_mo', 'tang_suc_manh', 'duy_tri'])->default('duy_tri');
            $table->integer('so_ngay')->default(3); // Số buổi tập mỗi tuần
            $table->timestamps();
        });

        Schema::create('chi_tiet_giao_an', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_giao_an')->constrained('giao_an_tap')->onDelete('cascade');
            $table->foreignId('id_bai_tap')->constrained('bai_tap')->onDelete('cascade');
            $table->integer('ngay_tap'); // Ngày thứ mấy trong chu kỳ (1, 2, 3...)
            $table->integer('thu_tu'); // Thứ tự bài trong buổi tập
            $table->integer('so_hiep')->default(3);
            $table->string('so_lan')->default('10-12'); // Reps (có thể là range)
            $table->string('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_giao_an');
        Schema::dropIfExists('giao_an_tap');
    }
};
