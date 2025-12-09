<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing customers that don't have thoi_han_thang set
        DB::table('khach_hang')
            ->whereNull('thoi_han_thang')
            ->update([
                'thoi_han_thang' => 3,
                'updated_at' => now(),
            ]);
        
        // Calculate ngay_het_han for customers that don't have it
        $khachHangs = DB::table('khach_hang')
            ->whereNull('ngay_het_han')
            ->whereNotNull('created_at')
            ->get();
        
        foreach ($khachHangs as $kh) {
            $createdAt = \Carbon\Carbon::parse($kh->created_at);
            $thoiHanThang = $kh->thoi_han_thang ?? 3;
            $ngayHetHan = $createdAt->copy()->addMonths($thoiHanThang);
            
            DB::table('khach_hang')
                ->where('id', $kh->id)
                ->update([
                    'ngay_het_han' => $ngayHetHan,
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data migration - cannot be reversed
        // If needed, manually update the records
    }
};
