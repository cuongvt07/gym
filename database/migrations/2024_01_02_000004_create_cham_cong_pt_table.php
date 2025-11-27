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
        Schema::create('cham_cong_pt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pt')->constrained('pt')->onDelete('cascade');
            $table->foreignId('id_lich_tap')->constrained('lich_tap')->onDelete('cascade');
            $table->date('ngay_lam');
            $table->time('gio_bat_dau');
            $table->time('gio_ket_thuc');
            $table->decimal('so_gio_lam', 5, 2);
            $table->string('loai_cong')->default('day_hoc'); // teaching, training, etc.
            $table->enum('trang_thai', ['chua_duyet', 'da_duyet'])->default('chua_duyet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cham_cong_pt');
    }
};
