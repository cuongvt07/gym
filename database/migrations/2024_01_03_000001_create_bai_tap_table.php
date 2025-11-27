<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bai_tap', function (Blueprint $table) {
            $table->id();
            $table->string('ten_bai_tap');
            $table->string('nhom_co'); // nguc, lung, chan, tay, vai, bung, cardio
            $table->enum('do_kho', ['de', 'trung_binh', 'kho'])->default('trung_binh');
            $table->text('mo_ta')->nullable();
            $table->string('video_url')->nullable();
            $table->string('hinh_anh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bai_tap');
    }
};
