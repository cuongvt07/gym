<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiaoAnTap extends Model
{
    use HasFactory;

    protected $table = 'giao_an_tap';

    protected $fillable = [
        'ten_giao_an',
        'mo_ta',
        'muc_tieu',
        'so_ngay',
    ];

    public function chiTiet()
    {
        return $this->hasMany(ChiTietGiaoAn::class, 'id_giao_an')->orderBy('ngay_tap')->orderBy('thu_tu');
    }

    public function baiTaps()
    {
        return $this->belongsToMany(BaiTap::class, 'chi_tiet_giao_an', 'id_giao_an', 'id_bai_tap')
            ->withPivot(['ngay_tap', 'thu_tu', 'so_hiep', 'so_lan', 'ghi_chu']);
    }
}
