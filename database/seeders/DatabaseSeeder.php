<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\NguoiDung;
use App\Models\Pt;
use App\Models\KhachHang;
use App\Models\GoiTap;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin user
        $admin = NguoiDung::create([
            'ho_ten' => 'Admin Joe Fitness',
            'email' => 'admin@joefitness.com',
            'password' => Hash::make('password'),
            'sdt' => '0123456789',
            'ngay_sinh' => '1990-01-01',
            'gioi_tinh' => 'nam',
            'role' => 'admin',
        ]);

        // Create PT users
        $pt1User = NguoiDung::create([
            'ho_ten' => 'Nguyễn Văn A',
            'email' => 'pt1@joefitness.com',
            'password' => Hash::make('password'),
            'sdt' => '0987654321',
            'ngay_sinh' => '1995-05-15',
            'gioi_tinh' => 'nam',
            'role' => 'pt',
        ]);

        Pt::create([
            'id_nguoi_dung' => $pt1User->id,
            'luong_co_ban' => 5000000,
            'luong_gio' => 100000,
            'so_gio_tieu_chuan' => 160,
            'kinh_nghiem' => '5 năm kinh nghiệm huấn luyện Gym và Boxing',
            'chung_chi' => 'Chứng chỉ PT quốc tế ACE, NASM',
            'chuyen_mon' => ['Gym', 'Boxing'],
            'trang_thai' => 'hoat_dong',
        ]);

        $pt2User = NguoiDung::create([
            'ho_ten' => 'Trần Thị B',
            'email' => 'pt2@joefitness.com',
            'password' => Hash::make('password'),
            'sdt' => '0976543210',
            'ngay_sinh' => '1993-08-20',
            'gioi_tinh' => 'nu',
            'role' => 'pt',
        ]);

        Pt::create([
            'id_nguoi_dung' => $pt2User->id,
            'luong_co_ban' => 4500000,
            'luong_gio' => 90000,
            'so_gio_tieu_chuan' => 160,
            'kinh_nghiem' => '3 năm kinh nghiệm huấn luyện Yoga và Cardio',
            'chung_chi' => 'Chứng chỉ Yoga RYT-200',
            'chuyen_mon' => ['Yoga', 'Cardio'],
            'trang_thai' => 'hoat_dong',
        ]);

        // Create Customer users
        for ($i = 1; $i <= 5; $i++) {
            $userKH = NguoiDung::create([
                'ho_ten' => "Khách hàng {$i}",
                'email' => "customer{$i}@example.com",
                'password' => Hash::make('password'),
                'sdt' => '091234567' . $i,
                'ngay_sinh' => '1998-0' . $i . '-10',
                'gioi_tinh' => $i % 2 == 0 ? 'nu' : 'nam',
                'role' => 'user',
            ]);

            KhachHang::create([
                'id_nguoi_dung' => $userKH->id,
                'ma_the' => 'KH' . time() . $i,
                'trang_thai_the' => 'hoat_dong',
            ]);
        }

        // Create Training Packages
        $packages = [
            [
                'ten_goi' => 'Gói 10 buổi',
                'so_buoi' => 10,
                'gia' => 1500000,
                'mo_ta' => 'Gói tập cơ bản với 10 buổi tập cùng PT',
                'trang_thai' => 'hoat_dong',
            ],
            [
                'ten_goi' => 'Gói 20 buổi',
                'so_buoi' => 20,
                'gia' => 2800000,
                'mo_ta' => 'Gói tập tiết kiệm với 20 buổi tập cùng PT',
                'trang_thai' => 'hoat_dong',
            ],
            [
                'ten_goi' => 'Gói 30 buổi',
                'so_buoi' => 30,
                'gia' => 4000000,
                'mo_ta' => 'Gói tập tốt nhất với 30 buổi tập cùng PT',
                'trang_thai' => 'hoat_dong',
            ],
            [
                'ten_goi' => 'Gói 50 buổi VIP',
                'so_buoi' => 50,
                'gia' => 6500000,
                'mo_ta' => 'Gói tập VIP với 50 buổi tập cùng PT và chế độ tập luyện đặc biệt',
                'trang_thai' => 'hoat_dong',
            ],
        ];

        foreach ($packages as $package) {
            GoiTap::create($package);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@joefitness.com / password');
        $this->command->info('PT1: pt1@joefitness.com / password');
        $this->command->info('PT2: pt2@joefitness.com / password');
        $this->command->info('Customer: customer1@example.com / password');
    }
}
