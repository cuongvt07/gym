<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Placeholder for dashboard statistics
        $stats = [
            'total_pt' => \App\Models\Pt::count(),
            'total_customers' => \App\Models\KhachHang::count(),
            'total_packages' => \App\Models\GoiTap::count(),
            'active_customers' => \App\Models\KhachHang::hoatDong()->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
