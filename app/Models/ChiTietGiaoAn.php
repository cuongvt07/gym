<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietGiaoAn extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_giao_an';

    protected $fillable = [
        'id_giao_an',
        'id_bai_tap',
        'ngay_tap',
        'thu_tu',
        'so_hiep',
        'so_lan',
        'ghi_chu',
    ];

    public function giaoAn()
    {
        return $this->belongsTo(GiaoAnTap::class, 'id_giao_an');
    }

    public function baiTap()
    {
        return $this->belongsTo(BaiTap::class, 'id_bai_tap');
    }
}
