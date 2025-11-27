<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DangKyGoi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dang_ky_goi';

    protected $fillable = [
        'id_khach_hang',
        'id_goi_tap',
        'id_pt',
        'tong_buoi',
        'buoi_da_tap',
        'buoi_con_lai',
        'ngay_dang_ky',
        'trang_thai',
        'trang_thai_thanh_toan',
    ];

    protected $casts = [
        'ngay_dang_ky' => 'date',
    ];

    /**
     * Relationships
     */
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'id_khach_hang');
    }

    public function goiTap()
    {
        return $this->belongsTo(GoiTap::class, 'id_goi_tap');
    }

    public function pt()
    {
        return $this->belongsTo(Pt::class, 'id_pt');
    }

    public function lichTap()
    {
        return $this->hasMany(LichTap::class, 'id_dang_ky_goi');
    }

    /**
     * Scopes
     */
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }

    public function scopeHetHan($query)
    {
        return $query->where('trang_thai', 'het_han');
    }

    public function scopeDaThanhToan($query)
    {
        return $query->where('trang_thai_thanh_toan', 'da_thanh_toan');
    }

    /**
     * Methods
     */
    public function hasSessionsRemaining()
    {
        return $this->buoi_con_lai > 0;
    }

    public function deductSession()
    {
        $this->buoi_da_tap++;
        $this->buoi_con_lai--;
        
        // Auto-expire if no sessions left
        if ($this->buoi_con_lai == 0) {
            $this->trang_thai = 'het_han';
        }
        
        $this->save();
    }

    public function getTienDoAttribute()
    {
        if ($this->tong_buoi == 0) return 0;
        return round(($this->buoi_da_tap / $this->tong_buoi) * 100, 2);
    }
}
