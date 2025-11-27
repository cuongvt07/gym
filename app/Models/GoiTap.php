<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoiTap extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'goi_tap';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'ten_goi',
        'so_buoi',
        'gia',
        'mo_ta',
        'trang_thai',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'gia' => 'decimal:2',
        ];
    }

    /**
     * Scope for active packages
     */
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }

    /**
     * Get formatted price
     */
    public function getGiaFormatAttribute()
    {
        return number_format($this->gia, 0, ',', '.') . ' VNĐ';
    }
}
