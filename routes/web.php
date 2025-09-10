<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Staff\DashboardController as StaffDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

// Default dashboard route (will be handled by middleware in dashboard route)
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasAnyRole(['Kepala Departemen', 'Kepala Divisi', 'Supervisor'])) {
        return redirect()->route('approval.dashboard');
    } elseif ($user->hasRole('Finance')) {
        return redirect()->route('finance.dashboard');
    } else {
        return redirect()->route('staff.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Profile routes (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

    // Staff Routes  
    Route::prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    });

    // Approval Routes
    Route::prefix('approval')->name('approval.')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    });

    // Finance Routes
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    });
});

// Include Breeze auth routes
require __DIR__.'/auth.php';