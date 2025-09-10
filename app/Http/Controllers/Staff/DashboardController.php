<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'welcome_message' => 'Selamat datang, ' . $user->nama,
            'user_info' => $user,
        ];

        return view('staff.dashboard', compact('stats'));
    }
}