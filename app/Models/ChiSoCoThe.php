<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiSoCoThe extends Model
{
    use HasFactory;

    protected $table = 'chi_so_co_the';

    protected $fillable = [
        'id_khach_hang',
        'id_pt',
        'ngay_do',
        'can_nang',
        'chieu_cao',
        'bmi',
        'ty_le_mo',
        'khoi_luong_co',
        'vong_eo',
        'vong_hong',
        'vong_nguc',
        'vong_dui',
        'vong_baptay',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_do' => 'date',
        'can_nang' => 'decimal:2',
        'chieu_cao' => 'decimal:2',
        'bmi' => 'decimal:1',
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'id_khach_hang');
    }

    public function pt()
    {
        return $this->belongsTo(Pt::class, 'id_pt');
    }

    // Auto calculate BMI if not set
    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->bmi) && $model->can_nang && $model->chieu_cao) {
                // BMI = kg / m^2
                $heightM = $model->chieu_cao / 100;
                $model->bmi = round($model->can_nang / ($heightM * $heightM), 1);
            }
        });
    }
}
