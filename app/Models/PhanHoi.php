<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanHoi extends Model
{
    use HasFactory;

    protected $table = 'phan_hoi';

    protected $fillable = [
        'id_nguoi_dung',
        'tieu_de',
        'noi_dung',
        'loai',
        'trang_thai',
        'phan_hoi_lai',
        'ngay_gui',
    ];

    protected $casts = [
        'ngay_gui' => 'datetime',
    ];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }
}
