<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class LichTap extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lich_tap';

    protected $fillable = [
        'id_dang_ky_goi',
        'id_pt',
        'id_khach_hang',
        'ngay_tap',
        'gio_bat_dau',
        'gio_ket_thuc',
        'ghi_chu',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_tap' => 'date',
    ];

    /**
     * Relationships
     */
    public function dangKyGoi()
    {
        return $this->belongsTo(DangKyGoi::class, 'id_dang_ky_goi');
    }

    public function pt()
    {
        return $this->belongsTo(Pt::class, 'id_pt');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'id_khach_hang');
    }

    public function diemDanh()
    {
        return $this->hasOne(DiemDanh::class, 'id_lich_tap');
    }

    public function chamCongPt()
    {
        return $this->hasOne(ChamCongPt::class, 'id_lich_tap');
    }

    /**
     * Scopes
     */
    public function scopeToday($query)
    {
        return $query->whereDate('ngay_tap', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('ngay_tap', '>=', today())
                     ->where('trang_thai', 'da_xep')
                     ->orderBy('ngay_tap')
                     ->orderBy('gio_bat_dau');
    }

    public function scopeByPt($query, $ptId)
    {
        return $query->where('id_pt', $ptId);
    }

    public function scopeByCustomer($query, $khachHangId)
    {
        return $query->where('id_khach_hang', $khachHangId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('ngay_tap', $date);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('trang_thai', $status);
    }

    /**
     * Validation methods
     */
    public static function validateNoConflict($ptId, $date, $start, $end, $excludeId = null)
    {
        $query = self::where('id_pt', $ptId)
            ->whereDate('ngay_tap', $date)
            ->whereIn('trang_thai', ['da_xep', 'da_hoc'])
            ->where(function($q) use ($start, $end) {
                $q->where(function($q2) use ($start, $end) {
                    // New session starts during existing session
                    $q2->where('gio_bat_dau', '<=', $start)
                       ->where('gio_ket_thuc', '>', $start);
                })->orWhere(function($q2) use ($start, $end) {
                    // New session ends during existing session
                    $q2->where('gio_bat_dau', '<', $end)
                       ->where('gio_ket_thuc', '>=', $end);
                })->orWhere(function($q2) use ($start, $end) {
                    // New session completely contains existing session
                    $q2->where('gio_bat_dau', '>=', $start)
                       ->where('gio_ket_thuc', '<=', $end);
                });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    /**
     * Helpers
     */
    public function getDurationInHours()
    {
        $start = Carbon::parse($this->gio_bat_dau);
        $end = Carbon::parse($this->gio_ket_thuc);
        return $start->diffInHours($end, true);
    }

    public function hasAttendance()
    {
        return $this->diemDanh()->exists();
    }

    public function canCheckIn()
    {
        return $this->trang_thai == 'da_xep' 
            && $this->ngay_tap->isToday() 
            && !$this->hasAttendance();
    }
}
