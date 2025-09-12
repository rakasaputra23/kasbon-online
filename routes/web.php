<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\PermissionController;

// ==========================
// 🔐 AUTH ROUTES
// ==========================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetPasswordEmail'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// ==========================
// 🔒 PROTECTED ROUTES
// ==========================
Route::middleware(['auth', 'permission'])->group(function () {

    // ==========================
    // 🏠 DASHBOARD
    // ==========================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PROFILE ROUTES
    Route::prefix('profile')->group(function () {
        Route::get('/', [UserController::class, 'profile'])->name('profile');
        Route::get('/edit', [UserController::class, 'editProfile'])->name('profile.edit');
        Route::put('/', [UserController::class, 'updateProfile'])->name('profile.update');
    });

    // ==========================
    // 👥 USER MANAGEMENT
    // ==========================
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/data', [UserController::class, 'getData'])->name('getData');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });


    Route::get('/user-group', [UserGroupController::class, 'index'])->name('user.group');
    Route::prefix('user-group')->name('user.group.')->group(function () {
        Route::get('/data', [UserGroupController::class, 'getData'])->name('getData');
        Route::post('/', [UserGroupController::class, 'store'])->name('store');
        Route::get('/{id}', [UserGroupController::class, 'show'])->name('show');
        Route::put('/{id}', [UserGroupController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserGroupController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/permissions', [UserGroupController::class, 'getPermissions'])->name('permissions');
    });

    // ==========================
    // 🔑 PERMISSION MANAGEMENT
    // ==========================
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/data', [PermissionController::class, 'getData'])->name('getData');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    });

});

// ==========================
// 🔄 REDIRECTS
// ==========================

// Route root - redirect ke halaman yang user bisa akses
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    $user = Auth::user();
    
    // Jika superadmin, langsung ke dashboard
    if ($user->isSuperAdmin()) {
        return redirect()->route('dashboard');
    }
    
    // Cari route pertama yang bisa diakses
    $firstAccessibleRoute = $user->getFirstAccessibleRoute();
    
    if ($firstAccessibleRoute) {
        return redirect()->route($firstAccessibleRoute);
    }
    
    // Jika tidak ada permission sama sekali, logout
    Auth::logout();
    return redirect()->route('login')->with('error', 'Akun Anda tidak memiliki akses ke sistem. Silakan hubungi administrator.');
    
})->middleware('auth')->name('root');

// Route home - sama seperti root
Route::get('/home', function () {
    return redirect('/');
})->middleware('auth')->name('home');