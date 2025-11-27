<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiemDanh extends Model
{
    use HasFactory;

    protected $table = 'diem_danh';

    protected $fillable = [
        'id_lich_tap',
        'id_khach_hang',
        'ngay_diem_danh',
        'gio_check_in',
        'gio_check_out',
    ];

    protected $casts = [
        'ngay_diem_danh' => 'date',
    ];

    /**
     * Relationships
     */
    public function lichTap()
    {
        return $this->belongsTo(LichTap::class, 'id_lich_tap');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'id_khach_hang');
    }

    /**
     * Methods
     */
    public function checkIn()
    {
        $this->gio_check_in = now()->format('H:i:s');
        $this->ngay_diem_danh = today();
        $this->save();
    }

    public function checkOut()
    {
        $this->gio_check_out = now()->format('H:i:s');
        $this->save();
    }

    public function getDuration()
    {
        if (!$this->gio_check_out) {
            return null;
        }

        $checkIn = Carbon::parse($this->gio_check_in);
        $checkOut = Carbon::parse($this->gio_check_out);
        
        return $checkIn->diff($checkOut);
    }

    public function getDurationInMinutes()
    {
        if (!$this->gio_check_out) {
            return 0;
        }

        $checkIn = Carbon::parse($this->gio_check_in);
        $checkOut = Carbon::parse($this->gio_check_out);
        
        return $checkIn->diffInMinutes($checkOut);
    }

    public function getDurationInHours()
    {
        if (!$this->gio_check_out) {
            return 0;
        }

        $checkIn = Carbon::parse($this->gio_check_in);
        $checkOut = Carbon::parse($this->gio_check_out);
        
        return $checkIn->diffInHours($checkOut, true);
    }

    public function isCheckedOut()
    {
        return !is_null($this->gio_check_out);
    }

    public function isCheckedIn()
    {
        return !is_null($this->gio_check_in);
    }
}
