<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phan_hoi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nguoi_dung')->constrained('nguoi_dung')->onDelete('cascade'); // Sender
            $table->string('tieu_de');
            $table->text('noi_dung');
            $table->enum('loai', ['gop_y', 'khieu_nai', 'khen_ngoi', 'khac'])->default('gop_y');
            $table->enum('trang_thai', ['cho_xu_ly', 'dang_xu_ly', 'da_xu_ly'])->default('cho_xu_ly');
            $table->text('phan_hoi_lai')->nullable(); // Admin response
            $table->timestamp('ngay_gui')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phan_hoi');
    }
};
