<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KhachHang extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'khach_hang';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_nguoi_dung',
        'ma_the',
        'trang_thai_the',
    ];

    /**
     * Relationship to NguoiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    /**
     * Relationship to DangKyGoi (Package Subscriptions)
     */
    public function dangKyGoi()
    {
        return $this->hasMany(DangKyGoi::class, 'id_khach_hang');
    }

    /**
     * Scope for active cards
     */
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai_the', 'hoat_dong');
    }

    /**
     * Scope for searching
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('ma_the', 'like', "%{$search}%")
                ->orWhereHas('nguoiDung', function($q) use ($search) {
                    $q->where('ho_ten', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('sdt', 'like', "%{$search}%");
                });
        }
        return $query;
    }

    /**
     * Generate card number (KH + timestamp)
     */
    public static function generateMaThe(): string
    {
        return 'KH' . time();
    }

    /**
     * Get full name from nguoi_dung
     */
    public function getHoTenAttribute()
    {
        return $this->nguoiDung->ho_ten ?? '';
    }

    /**
     * Get email from nguoi_dung
     */
    public function getEmailAttribute()
    {
        return $this->nguoiDung->email ?? '';
    }

    /**
     * Get phone from nguoi_dung
     */
    public function getSdtAttribute()
    {
        return $this->nguoiDung->sdt ?? '';
    }

    /**
     * Get avatar from nguoi_dung
     */
    public function getAvatarAttribute()
    {
        return $this->nguoiDung->avatar ?? 'default-avatar.png';
    }
}
