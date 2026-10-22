<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HowItWorksController;
use App\Http\Controllers\ServicesPageController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\FindParkingController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\ParkingDetailController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Requirement #8: Landing Page has only TWO primary buttons on the first screen: LOGIN and REGISTER.
Route::get('/', [AuthController::class, 'landing'])->name('home');

// Public Services Page (Requirement: /services & /services/{slug})
Route::get('/services', [ServicesPageController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServicesPageController::class, 'show'])->name('services.show');

Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::redirect('/about-us', '/about');

// Public How It Works Page
Route::get('/how-it-works', [HowItWorksController::class, 'index'])->name('how-it-works');

// Convenient redirects for Admin Panel
Route::redirect('/admin-panel', '/admin');
Route::redirect('/adminpanel', '/admin');

Route::middleware('guest:web')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showLogin'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Protected Routes (/dashboard, /find-parking, etc.)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'user.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');

    // Parking Discovery & Zone/Slot Visual Grid
    Route::get('/find-parking', [FindParkingController::class, 'index'])->name('user.find-parking');
    Route::get('/parking/{id}', [ParkingDetailController::class, 'show'])->name('user.parking.show');
    Route::get('/parking/{id}/zone/{zone}', [ParkingDetailController::class, 'showZoneSlots'])->name('user.parking.zone-slots');

    // Booking & Review Flow
    Route::get('/booking/review', [BookingController::class, 'review'])->name('user.booking.review');
    Route::post('/booking/create', [BookingController::class, 'store'])->name('user.booking.store');

    // Payment Flow
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('user.payment.show');
    Route::post('/payment/{booking}', [PaymentController::class, 'process'])->name('user.payment.process');
    Route::get('/booking/success/{booking}', [PaymentController::class, 'success'])->name('user.booking.success');

    // My Bookings & Receipt
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('user.my-bookings');
    Route::get('/my-bookings/{id}', [BookingController::class, 'show'])->name('user.booking.detail');
    Route::get('/my-bookings/{id}/receipt', [BookingController::class, 'receipt'])->name('user.booking.receipt');
    Route::post('/my-bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('user.booking.cancel');

    // My Vehicles
    Route::get('/my-vehicles', [VehicleController::class, 'index'])->name('user.vehicles');
    Route::post('/my-vehicles', [VehicleController::class, 'store'])->name('user.vehicles.store');
    Route::post('/my-vehicles/{id}/default', [VehicleController::class, 'setDefault'])->name('user.vehicles.default');
    Route::delete('/my-vehicles/{id}', [VehicleController::class, 'destroy'])->name('user.vehicles.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('user.notifications');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'profile'])->name('user.profile');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('user.settings');
    Route::put('/settings/password', [ProfileController::class, 'updatePassword'])->name('user.settings.password');
});
