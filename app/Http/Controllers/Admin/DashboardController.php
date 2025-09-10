<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_groups' => UserGroup::count(),
            'active_users' => User::count(), // Adjust based on your active field
            'recent_users' => User::latest()->limit(5)->with('userGroup')->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}