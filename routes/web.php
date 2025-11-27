<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PtController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\Admin\GoiTapController;
use App\Http\Controllers\Admin\LichTapController;
use App\Http\Controllers\Admin\ChamCongPtController;
use App\Http\Controllers\Admin\BaiTapController;
use App\Http\Controllers\Admin\GiaoAnController;
use App\Http\Controllers\Admin\ChiSoCoTheController;
use App\Http\Controllers\Admin\ThanhToanController;
use App\Http\Controllers\Admin\BaoCaoController;
use App\Http\Controllers\User\DiemDanhController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'check.role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // PT Management
    Route::resource('pt', PtController::class);
    Route::post('pt/{pt}/toggle-status', [PtController::class, 'toggleStatus'])->name('pt.toggle-status');
    
    // Customer Management
    Route::resource('khach-hang', KhachHangController::class);
    Route::post('khach-hang/{khachHang}/toggle-card', [KhachHangController::class, 'toggleCard'])->name('khach-hang.toggle-card');
    Route::get('khach-hang/{khachHang}/assign-package', [KhachHangController::class, 'assignPackage'])->name('khach-hang.assign-package');
    Route::post('khach-hang/{khachHang}/assign-package', [KhachHangController::class, 'storePackage'])->name('khach-hang.store-package');
    
    // Quản lý Gói tập
    Route::resource('goi-tap', GoiTapController::class);
    Route::post('goi-tap/{goiTap}/toggle-status', [GoiTapController::class, 'toggleStatus'])->name('goi-tap.toggle-status');

    // Quản lý Lịch tập
    Route::resource('lich-tap', LichTapController::class);
    Route::post('lich-tap/{lichTap}/mark-absent', [LichTapController::class, 'markAbsent'])->name('lich-tap.mark-absent');

    // Chấm công PT
    Route::resource('cham-cong', ChamCongPtController::class)->only(['index']);
    Route::post('cham-cong/{id}/approve', [ChamCongPtController::class, 'approve'])->name('cham-cong.approve');
    Route::post('cham-cong/approve-all', [ChamCongPtController::class, 'approveAll'])->name('cham-cong.approve-all');

    // Phase 3: Training Management
    Route::resource('bai-tap', BaiTapController::class);
    Route::resource('giao-an', GiaoAnController::class);
    Route::resource('chi-so', ChiSoCoTheController::class)->only(['index', 'create', 'store']);

    // Phase 4: Finance & Reports
    Route::resource('thanh-toan', ThanhToanController::class)->only(['index', 'create', 'store']);
    Route::get('thanh-toan/{thanhToan}/invoice', [ThanhToanController::class, 'invoice'])->name('thanh-toan.invoice');
    Route::get('bao-cao', [BaoCaoController::class, 'index'])->name('bao-cao.index');

    // Phase 5: Feedback
    Route::get('phan-hoi', [App\Http\Controllers\Admin\PhanHoiController::class, 'index'])->name('phan-hoi.index');
    Route::put('phan-hoi/{phanHoi}', [App\Http\Controllers\Admin\PhanHoiController::class, 'update'])->name('phan-hoi.update');
});

// PT Routes
Route::middleware(['auth', 'role:pt'])->prefix('pt')->name('pt.')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Pt\DashboardController::class, 'index'])->name('dashboard');
    Route::get('cham-cong', [App\Http\Controllers\Pt\ChamCongController::class, 'index'])->name('cham-cong.index');
    
    // PT Feedback (reuse User controller for submission)
    Route::get('phan-hoi', [App\Http\Controllers\User\PhanHoiController::class, 'index'])->name('phan-hoi.index');
    Route::get('phan-hoi/create', [App\Http\Controllers\User\PhanHoiController::class, 'create'])->name('phan-hoi.create');
    Route::post('phan-hoi', [App\Http\Controllers\User\PhanHoiController::class, 'store'])->name('phan-hoi.store');
});

// User Routes
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('dashboard', [DiemDanhController::class, 'dashboard'])->name('dashboard');
    Route::post('check-in', [DiemDanhController::class, 'checkIn'])->name('check-in');
    Route::post('check-out', [DiemDanhController::class, 'checkOut'])->name('check-out');
    
    // User Feedback
    Route::resource('phan-hoi', App\Http\Controllers\User\PhanHoiController::class)->only(['index', 'create', 'store']);
});
