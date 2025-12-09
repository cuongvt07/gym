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
        'thoi_han_thang',
        'ngay_het_han',
    ];

    /**
     * Default attributes
     */
    protected $attributes = [
        'thoi_han_thang' => 3,
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'ngay_het_han' => 'date',
        ];
    }

    /**
     * Get start date (using created_at)
     */
    public function getNgayBatDauAttribute()
    {
        return $this->created_at;
    }

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
     * Scope for VIP customers (active package)
     */
    public function scopeVip($query)
    {
        return $query->whereHas('dangKyGoi', function($q) {
            $q->where('trang_thai', 'hoat_dong');
        });
    }

    /**
     * Scope for expired cards
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('ngay_het_han')
            ->where('ngay_het_han', '<=', now());
    }

    /**
     * Check if customer is VIP
     */
    public function getIsVipAttribute()
    {
        return $this->dangKyGoi()->where('trang_thai', 'hoat_dong')->exists();
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
        return $this->nguoiDung->avatar;
    }

    /**
     * Check if card is expired
     */
    public function getIsExpiredAttribute()
    {
        if (!$this->ngay_het_han) {
            return false;
        }
        return $this->ngay_het_han->isPast();
    }

    /**
     * Get remaining days until expiration (integer only)
     */
    public function getSoNgayConLaiAttribute()
    {
        if (!$this->ngay_het_han) {
            return null;
        }
        
        if ($this->is_expired) {
            return 0;
        }
        
        // Return integer (ceiling to always show positive days remaining)
        $days = now()->diffInDays($this->ngay_het_han, false);
        return (int) ceil($days);
    }

    /**
     * Check and update card status if expired
     */
    public function checkAndUpdateExpiredStatus()
    {
        if ($this->is_expired && $this->trang_thai_the !== 'het_han') {
            $this->update(['trang_thai_the' => 'het_han']);
            return true;
        }
        return false;
    }

    /**
     * Calculate expiration date based on start date and months
     */
    public static function calculateExpirationDate($startDate, $months)
    {
        return \Carbon\Carbon::parse($startDate)->addMonths($months);
    }
}
