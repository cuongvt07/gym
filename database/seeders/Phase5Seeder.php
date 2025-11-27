<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhanHoi;
use App\Models\NguoiDung;

class Phase5Seeder extends Seeder
{
    public function run(): void
    {
        $users = NguoiDung::where('role', 'user')->get();
        $pts = NguoiDung::where('role', 'pt')->get();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run DatabaseSeeder first.');
            return;
        }

        // 1. Seed User Feedback
        foreach ($users as $user) {
            PhanHoi::create([
                'id_nguoi_dung' => $user->id,
                'tieu_de' => 'Góp ý về phòng tập',
                'noi_dung' => 'Máy chạy bộ số 3 hơi ồn, mong admin kiểm tra lại.',
                'loai' => 'gop_y',
                'trang_thai' => 'cho_xu_ly',
            ]);
        }

        // 2. Seed PT Feedback
        foreach ($pts as $pt) {
            PhanHoi::create([
                'id_nguoi_dung' => $pt->id,
                'tieu_de' => 'Yêu cầu sửa điều hòa',
                'noi_dung' => 'Điều hòa khu vực tạ đơn bị hỏng.',
                'loai' => 'khieu_nai',
                'trang_thai' => 'dang_xu_ly',
                'phan_hoi_lai' => 'Đã gọi thợ sửa, dự kiến xong trong chiều nay.',
            ]);
        }
        
        $this->command->info('Phase 5 data seeded successfully!');
    }
}
