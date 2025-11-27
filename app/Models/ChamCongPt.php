<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChamCongPt extends Model
{
    use HasFactory;

    protected $table = 'cham_cong_pt';

    protected $fillable = [
        'id_pt',
        'id_lich_tap',
        'ngay_lam',
        'gio_bat_dau',
        'gio_ket_thuc',
        'so_gio_lam',
        'loai_cong',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_lam' => 'date',
        'so_gio_lam' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function pt()
    {
        return $this->belongsTo(Pt::class, 'id_pt');
    }

    public function lichTap()
    {
        return $this->belongsTo(LichTap::class, 'id_lich_tap');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('trang_thai', 'chua_duyet');
    }

    public function scopeApproved($query)
    {
        return $query->where('trang_thai', 'da_duyet');
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('ngay_lam', $month)
                     ->whereYear('ngay_lam', $year);
    }

    public function scopeByPt($query, $ptId)
    {
        return $query->where('id_pt', $ptId);
    }

    /**
     * Methods
     */
    public function approve()
    {
        $this->trang_thai = 'da_duyet';
        $this->save();
    }

    public function calculateSalary()
    {
        $pt = $this->pt;
        return $this->so_gio_lam * $pt->luong_gio;
    }

    public function isPending()
    {
        return $this->trang_thai == 'chua_duyet';
    }

    public function isApproved()
    {
        return $this->trang_thai == 'da_duyet';
    }

    /**
     * Static methods for salary calculation
     */
    public static function calculateMonthlySalary($ptId, $month, $year)
    {
        $pt = Pt::findOrFail($ptId);
        
        // Get total approved hours
        $totalHours = self::byPt($ptId)
            ->byMonth($month, $year)
            ->approved()
            ->sum('so_gio_lam');
        
        // Calculate: base salary + (hourly rate * hours)
        return $pt->luong_co_ban + ($pt->luong_gio * $totalHours);
    }
}
