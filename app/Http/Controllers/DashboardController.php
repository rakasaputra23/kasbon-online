<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
// use App\Models\UserGroup; // Sesuaikan dengan nama model yang benar

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Total users
        $totalUsers = User::count();
        
        // Total user groups - sesuaikan dengan nama model dan tabel Anda
        // Opsi 1: Jika menggunakan model UserGroup
        // $totalUserGroups = UserGroup::count();
        
        // Opsi 2: Jika menggunakan relasi atau field group_id di users
        $totalUserGroups = User::distinct('user_group_id')->whereNotNull('user_group_id')->count();
        
        // Opsi 3: Jika menggunakan tabel terpisah dengan nama lain
        // $totalUserGroups = \DB::table('user_groups')->count();
        
        // Active users - karena tidak ada kolom is_active, kita hitung semua user
        // atau gunakan kondisi lain seperti email_verified_at tidak null
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        
        // Jika tidak ada email verification, gunakan total users saja
        // $activeUsers = $totalUsers;
        
        // Recent users - ambil user terbaru
        $recentUsers = User::latest('created_at')->take(8)->get();
        
        return view('dashboard', compact(
            'user', 
            'totalUsers', 
            'totalUserGroups', 
            'activeUsers', 
            'recentUsers'
        ));
    }
}