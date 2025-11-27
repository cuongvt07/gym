<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DangKyGoi;
use App\Models\ThanhToan;
use App\Models\NguoiDung;
use Carbon\Carbon;

class Phase4Seeder extends Seeder
{
    public function run(): void
    {
        // Find unpaid subscriptions
        $unpaidSubs = DangKyGoi::where('trang_thai_thanh_toan', 'chua_thanh_toan')->get();
        $admin = NguoiDung::where('role', 'admin')->first();

        if (!$admin) {
            $this->command->info('No admin found. Please run DatabaseSeeder first.');
            return;
        }

        foreach ($unpaidSubs as $sub) {
            // Simulate payment for some
            if (rand(0, 1)) {
                $amount = $sub->goiTap->gia;
                
                $thanhToan = ThanhToan::create([
                    'id_dang_ky_goi' => $sub->id,
                    'so_tien' => $amount,
                    'ngay_thanh_toan' => Carbon::now()->subDays(rand(0, 5)),
                    'phuong_thuc' => 'tien_mat',
                    'nguoi_thu' => $admin->id,
                    'ma_hoa_don' => ThanhToan::generateInvoiceCode(),
                    'ghi_chu' => 'Thanh toán tiền mặt (Seeded)',
                ]);

                $sub->update(['trang_thai_thanh_toan' => 'da_thanh_toan']);
                
                $this->command->info("Created payment for subscription #{$sub->id}");
            }
        }
        
        $this->command->info('Phase 4 data seeded successfully!');
    }
}
