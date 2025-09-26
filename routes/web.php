<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PpkController;

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

    
    // ==========================
    // 📋 PPK MANAGEMENT - FIXED ROUTES ORDER
    // ==========================
    Route::get('/ppk', [PpkController::class, 'index'])->name('ppk.index');

    // ==========================
    // PPK AJAX ROUTES - DIRECT DEFINITION (MOVED OUTSIDE PREFIX GROUP)
    // ==========================
    Route::get('/ppk/data', [PpkController::class, 'getData'])->name('ppk.data');
    Route::get('/ppk/divisi-options', [PpkController::class, 'getDivisiOptions'])->name('ppk.divisi-options');
    Route::get('/ppk/approval-stats', [PpkController::class, 'getApprovalStats'])->name('ppk.approval-stats');
    Route::get('/ppk/next-number', [PpkController::class, 'getNextNumber'])->name('ppk.getNextNumber');
    Route::get('/ppk/divisi-list', [PpkController::class, 'getDivisiList'])->name('ppk.getDivisiList');
    Route::get('/ppk/recent-activities', [PpkController::class, 'getRecentActivities'])->name('ppk.getRecentActivities');
    Route::get('/ppk/export', [PpkController::class, 'export'])->name('ppk.export');

    // ==========================
    // PPK CRUD ROUTES - FIXED ORDER WITH CONSTRAINTS
    // ==========================
    Route::prefix('ppk')->name('ppk.')->group(function () {
        // SPECIFIC ROUTES FIRST (before /{id} patterns)
        Route::get('/create', [PpkController::class, 'create'])->name('create');
        Route::post('/', [PpkController::class, 'store'])->name('store');
        
        // ADMIN CONFIG ROUTES (specific paths first)
        Route::middleware('can:admin-only')->group(function () {
            Route::get('/config/approval', [PpkController::class, 'approvalConfig'])->name('config.approval');
            Route::post('/config/approval', [PpkController::class, 'saveApprovalConfig'])->name('config.approval.save');
            Route::get('/reports', [PpkController::class, 'reports'])->name('reports');
            Route::get('/analytics', [PpkController::class, 'analytics'])->name('analytics');
        });
        
        // BULK OPERATIONS (specific paths)
        Route::post('/bulk-action', [PpkController::class, 'bulkAction'])->name('bulkAction');
        
        // ==========================
        // CRITICAL POST ACTIONS WITH CONSTRAINTS - MUST BE BEFORE GET /{id}
        // ==========================
        Route::post('/{id}/submit', [PpkController::class, 'submit'])
             ->name('submit')
             ->where('id', '[0-9]+');
             
        Route::post('/{id}/approve', [PpkController::class, 'approve'])
             ->name('approve')
             ->where('id', '[0-9]+');
             
        Route::post('/{id}/reject', [PpkController::class, 'reject'])
             ->name('reject')
             ->where('id', '[0-9]+');
        
        // OTHER HTTP METHODS WITH CONSTRAINTS
        Route::put('/{id}', [PpkController::class, 'update'])
             ->name('update')
             ->where('id', '[0-9]+');
             
        Route::delete('/{id}', [PpkController::class, 'destroy'])
             ->name('destroy')
             ->where('id', '[0-9]+');
        
        // ==========================
        // GET ROUTES WITH {id} PATTERNS - CONSTRAINED
        // ==========================
        Route::get('/{id}/edit', [PpkController::class, 'edit'])
             ->name('edit')
             ->where('id', '[0-9]+');
             
        Route::get('/{id}/approval', [PpkController::class, 'showApproval'])
             ->name('approval')
             ->where('id', '[0-9]+');
             
        Route::get('/{id}/approval-history', [PpkController::class, 'getApprovalHistory'])
             ->name('approvalHistory')
             ->where('id', '[0-9]+');
             
        Route::get('/{id}/lampiran/edit', [PpkController::class, 'editLampiran'])
             ->name('lampiran.edit')
             ->where('id', '[0-9]+');
             
        Route::get('/{id}/pdf', [PpkController::class, 'generatePdf'])
             ->name('pdf')
             ->where('id', '[0-9]+');
        
        // ==========================
        // CATCH-ALL ROUTE - MUST BE ABSOLUTE LAST WITH CONSTRAINT
        // ==========================
        Route::get('/{id}', [PpkController::class, 'show'])
             ->name('show')
             ->where('id', '[0-9]+');
    });

});

// ==========================
// 🔄 REDIRECTS & ROOT ROUTES
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
    
    // Refresh user relations untuk memastikan data terbaru
    $user->refreshRelations();
    
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

// ==========================
// 🔧 API ROUTES (Optional)
// ==========================
Route::prefix('api')->middleware(['auth:sanctum', 'permission'])->group(function () {
    Route::prefix('ppk')->group(function () {
        Route::get('/stats', [PpkController::class, 'getApprovalStats']);
        Route::get('/activities', [PpkController::class, 'getRecentActivities']);
        Route::get('/{id}/timeline', [PpkController::class, 'getApprovalTimeline']);
    });
});

// ==========================
// 🚫 FALLBACK ROUTE
// ==========================
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard')->with('warning', 'Halaman yang Anda cari tidak ditemukan.');
    }
    return redirect()->route('login')->with('error', 'Halaman tidak ditemukan. Silakan login terlebih dahulu.');
});