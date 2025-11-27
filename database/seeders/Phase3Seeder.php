<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BaiTap;
use App\Models\GiaoAnTap;
use App\Models\ChiTietGiaoAn;
use App\Models\ChiSoCoThe;
use App\Models\KhachHang;
use App\Models\Pt;
use Carbon\Carbon;

class Phase3Seeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Exercises
        $exercises = [
            [
                'ten_bai_tap' => 'Bench Press (Đẩy ngực)',
                'nhom_co' => 'nguc',
                'do_kho' => 'trung_binh',
                'mo_ta' => 'Nằm trên ghế phẳng, đẩy tạ đòn lên xuống.',
                'video_url' => 'https://www.youtube.com/watch?v=rT7DgCr-3pg',
            ],
            [
                'ten_bai_tap' => 'Squat (Gánh tạ)',
                'nhom_co' => 'chan',
                'do_kho' => 'kho',
                'mo_ta' => 'Đứng thẳng, gánh tạ trên vai, ngồi xổm xuống và đứng lên.',
                'video_url' => 'https://www.youtube.com/watch?v=aclHkVaku9U',
            ],
            [
                'ten_bai_tap' => 'Deadlift (Kéo tạ)',
                'nhom_co' => 'lung',
                'do_kho' => 'kho',
                'mo_ta' => 'Kéo tạ từ sàn lên đến ngang hông.',
                'video_url' => 'https://www.youtube.com/watch?v=op9kVnSso6Q',
            ],
            [
                'ten_bai_tap' => 'Pull Up (Hít xà)',
                'nhom_co' => 'lung',
                'do_kho' => 'trung_binh',
                'mo_ta' => 'Treo người trên xà, kéo người lên.',
                'video_url' => 'https://www.youtube.com/watch?v=eGo4IYlbE5g',
            ],
            [
                'ten_bai_tap' => 'Push Up (Hít đất)',
                'nhom_co' => 'nguc',
                'do_kho' => 'de',
                'mo_ta' => 'Nằm sấp, chống tay đẩy người lên.',
                'video_url' => 'https://www.youtube.com/watch?v=IODxDxX7oi4',
            ],
            [
                'ten_bai_tap' => 'Plank',
                'nhom_co' => 'bung',
                'do_kho' => 'de',
                'mo_ta' => 'Giữ người thẳng trên khuỷu tay và ngón chân.',
                'video_url' => 'https://www.youtube.com/watch?v=ASdvN_XEl_c',
            ],
        ];

        foreach ($exercises as $ex) {
            BaiTap::firstOrCreate(['ten_bai_tap' => $ex['ten_bai_tap']], $ex);
        }

        // 2. Seed Training Program
        $giaoAn = GiaoAnTap::create([
            'ten_giao_an' => 'Giáo án Tăng cơ cơ bản (3 ngày)',
            'mo_ta' => 'Chương trình tập luyện toàn thân cho người mới bắt đầu.',
            'muc_tieu' => 'tang_co',
            'so_ngay' => 3,
        ]);

        $baiTaps = BaiTap::all();
        
        // Day 1: Push (Ngực, Vai, Tay sau)
        ChiTietGiaoAn::create([
            'id_giao_an' => $giaoAn->id,
            'id_bai_tap' => $baiTaps->where('nhom_co', 'nguc')->first()->id, // Bench Press
            'ngay_tap' => 1,
            'thu_tu' => 1,
            'so_hiep' => 4,
            'so_lan' => '8-10',
            'ghi_chu' => 'Tăng tạ dần',
        ]);
        ChiTietGiaoAn::create([
            'id_giao_an' => $giaoAn->id,
            'id_bai_tap' => $baiTaps->where('ten_bai_tap', 'Push Up (Hít đất)')->first()->id,
            'ngay_tap' => 1,
            'thu_tu' => 2,
            'so_hiep' => 3,
            'so_lan' => 'Max',
        ]);

        // Day 2: Pull (Lưng, Tay trước)
        ChiTietGiaoAn::create([
            'id_giao_an' => $giaoAn->id,
            'id_bai_tap' => $baiTaps->where('ten_bai_tap', 'Deadlift (Kéo tạ)')->first()->id,
            'ngay_tap' => 2,
            'thu_tu' => 1,
            'so_hiep' => 3,
            'so_lan' => '5',
        ]);
        ChiTietGiaoAn::create([
            'id_giao_an' => $giaoAn->id,
            'id_bai_tap' => $baiTaps->where('ten_bai_tap', 'Pull Up (Hít xà)')->first()->id,
            'ngay_tap' => 2,
            'thu_tu' => 2,
            'so_hiep' => 3,
            'so_lan' => '8-10',
        ]);

        // Day 3: Legs & Abs
        ChiTietGiaoAn::create([
            'id_giao_an' => $giaoAn->id,
            'id_bai_tap' => $baiTaps->where('ten_bai_tap', 'Squat (Gánh tạ)')->first()->id,
            'ngay_tap' => 3,
            'thu_tu' => 1,
            'so_hiep' => 4,
            'so_lan' => '8-10',
        ]);
        ChiTietGiaoAn::create([
            'id_giao_an' => $giaoAn->id,
            'id_bai_tap' => $baiTaps->where('ten_bai_tap', 'Plank')->first()->id,
            'ngay_tap' => 3,
            'thu_tu' => 2,
            'so_hiep' => 3,
            'so_lan' => '60s',
        ]);

        // 3. Seed Body Metrics for Customer 1
        $kh1 = KhachHang::first();
        $pt1 = Pt::first();

        if ($kh1 && $pt1) {
            // Month ago
            ChiSoCoThe::create([
                'id_khach_hang' => $kh1->id,
                'id_pt' => $pt1->id,
                'ngay_do' => Carbon::now()->subMonth(),
                'can_nang' => 75.0,
                'chieu_cao' => 175.0,
                'ty_le_mo' => 20.0,
                'khoi_luong_co' => 30.0,
            ]);

            // 2 weeks ago
            ChiSoCoThe::create([
                'id_khach_hang' => $kh1->id,
                'id_pt' => $pt1->id,
                'ngay_do' => Carbon::now()->subWeeks(2),
                'can_nang' => 74.0,
                'chieu_cao' => 175.0,
                'ty_le_mo' => 19.0,
                'khoi_luong_co' => 30.5,
            ]);

            // Today
            ChiSoCoThe::create([
                'id_khach_hang' => $kh1->id,
                'id_pt' => $pt1->id,
                'ngay_do' => Carbon::now(),
                'can_nang' => 73.5,
                'chieu_cao' => 175.0,
                'ty_le_mo' => 18.5,
                'khoi_luong_co' => 31.0,
            ]);
        }

        $this->command->info('Phase 3 data seeded successfully!');
    }
}
