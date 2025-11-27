<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pt extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'pt';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'id_nguoi_dung',
        'luong_co_ban',
        'luong_gio',
        'so_gio_tieu_chuan',
        'kinh_nghiem',
        'chung_chi',
        'chuyen_mon',
        'trang_thai',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'luong_co_ban' => 'decimal:2',
            'luong_gio' => 'decimal:2',
            'chuyen_mon' => 'array',
        ];
    }

    /**
     * Relationship to NguoiDung
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'id_nguoi_dung');
    }

    /**
     * Scope for active PTs
     */
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }

    /**
     * Scope for searching
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->whereHas('nguoiDung', function($q) use ($search) {
                $q->where('ho_ten', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('sdt', 'like', "%{$search}%");
            });
        }
        return $query;
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
