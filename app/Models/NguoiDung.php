<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'nguoi_dung';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'password',
        'ho_ten',
        'sdt',
        'ngay_sinh',
        'gioi_tinh',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ngay_sinh' => 'date',
        ];
    }

    /**
     * Relationship to PT
     */
    public function pt()
    {
        return $this->hasOne(Pt::class, 'id_nguoi_dung');
    }

    /**
     * Relationship to KhachHang
     */
    public function khachHang()
    {
        return $this->hasOne(KhachHang::class, 'id_nguoi_dung');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is PT
     */
    public function isPt(): bool
    {
        return $this->role === 'pt';
    }

    /**
     * Check if user is customer
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin' => 'admin.dashboard',
            'pt' => 'pt.dashboard',
            'user' => 'user.dashboard',
            default => 'login',
        };
    }
}
