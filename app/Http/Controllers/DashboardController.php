<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserGroup;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Basic statistics
        $totalUsers = User::count();
        $totalUserGroups = UserGroup::count();
        $activeUsers = User::whereNotNull('email_verified_at')->count();
        
        // Recent users
        $recentUsers = User::with('userGroup')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'totalUsers', 
            'totalUserGroups', 
            'activeUsers',
            'recentUsers'
        ));
    }
}