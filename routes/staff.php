<?php

use App\Http\Controllers\Admin\EntryController;
use App\Http\Controllers\Admin\ExitController;
use App\Http\Controllers\Staff\StaffAuthController;
use App\Http\Controllers\Staff\StaffDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staff Routes (Prefix: /staff | Name: staff.)
|--------------------------------------------------------------------------
*/

Route::middleware('guest:staff')->group(function () {
    Route::get('/login', [StaffAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StaffAuthController::class, 'login']);
});

Route::middleware(['auth:staff', 'staff'])->group(function () {
    Route::post('/logout', [StaffAuthController::class, 'logout'])->name('logout');

    Route::get('/', [StaffDashboardController::class, 'index']);
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // Gate Operations
    Route::get('/entries', [EntryController::class, 'index'])->name('entries.index');
    Route::post('/entries/confirm', [EntryController::class, 'confirm'])->name('entries.confirm');

    Route::get('/exits', [ExitController::class, 'index'])->name('exits.index');
    Route::post('/exits/confirm', [ExitController::class, 'confirm'])->name('exits.confirm');
});
