<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Data yang benar-benar digunakan di dashboard
        $totalUsers = User::count();
        
        // Active users - gunakan kondisi yang aman
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        
        // Jika activeUsers 0, fallback ke total users
        if ($activeUsers == 0) {
            $activeUsers = $totalUsers;
        }
        
        return view('dashboard', compact(
            'user', 
            'totalUsers', 
            'activeUsers'
        ));
    }
}