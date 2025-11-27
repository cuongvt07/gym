<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    use HasFactory;

    protected $table = 'thanh_toan';

    protected $fillable = [
        'id_dang_ky_goi',
        'so_tien',
        'ngay_thanh_toan',
        'phuong_thuc',
        'nguoi_thu',
        'ma_hoa_don',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_thanh_toan' => 'datetime',
        'so_tien' => 'decimal:0',
    ];

    public function dangKyGoi()
    {
        return $this->belongsTo(DangKyGoi::class, 'id_dang_ky_goi');
    }

    public function nguoiThu()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_thu');
    }

    // Generate Invoice Code: INV-YYYYMMDD-XXX
    public static function generateInvoiceCode()
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $lastInvoice = self::where('ma_hoa_don', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->ma_hoa_don, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
