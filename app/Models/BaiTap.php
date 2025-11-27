<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiTap extends Model
{
    use HasFactory;

    protected $table = 'bai_tap';

    protected $fillable = [
        'ten_bai_tap',
        'nhom_co',
        'do_kho',
        'mo_ta',
        'video_url',
        'hinh_anh',
    ];

    // Scopes
    public function scopeByMuscleGroup($query, $group)
    {
        return $query->where('nhom_co', $group);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('do_kho', $difficulty);
    }
}
