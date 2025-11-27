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
        Schema::create('goi_tap', function (Blueprint $table) {
            $table->id();
            $table->string('ten_goi');
            $table->integer('so_buoi');
            $table->decimal('gia', 15, 2);
            $table->text('mo_ta')->nullable();
            $table->enum('trang_thai', ['hoat_dong', 'tam_ngung'])->default('hoat_dong');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goi_tap');
    }
};
