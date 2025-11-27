<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_so_co_the', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_khach_hang')->constrained('khach_hang')->onDelete('cascade');
            $table->foreignId('id_pt')->nullable()->constrained('pt')->onDelete('set null');
            $table->date('ngay_do');
            
            // Basic metrics
            $table->decimal('can_nang', 5, 2); // kg
            $table->decimal('chieu_cao', 5, 2); // cm
            $table->decimal('bmi', 4, 1)->nullable(); // Auto calculated usually
            
            // Advanced metrics (optional)
            $table->decimal('ty_le_mo', 4, 1)->nullable(); // %
            $table->decimal('khoi_luong_co', 5, 2)->nullable(); // kg
            
            // Measurements (optional)
            $table->decimal('vong_eo', 5, 1)->nullable(); // cm
            $table->decimal('vong_hong', 5, 1)->nullable(); // cm
            $table->decimal('vong_nguc', 5, 1)->nullable(); // cm
            $table->decimal('vong_dui', 5, 1)->nullable(); // cm
            $table->decimal('vong_baptay', 5, 1)->nullable(); // cm
            
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_so_co_the');
    }
};
