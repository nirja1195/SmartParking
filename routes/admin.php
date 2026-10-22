<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AiInsightsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AvailabilityController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EntryController;
use App\Http\Controllers\Admin\ExitController;
use App\Http\Controllers\Admin\HomepageCmsController;
use App\Http\Controllers\Admin\HowItWorksCmsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ParkingLocationController;
use App\Http\Controllers\Admin\ParkingServiceController;
use App\Http\Controllers\Admin\ParkingSlotController;
use App\Http\Controllers\Admin\ParkingZoneController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (Prefix: /admin | Name: admin.)
|--------------------------------------------------------------------------
*/

Route::middleware('guest:admin')->group(function () {
    Route::get('/login/otp', [\App\Http\Controllers\Admin\AdminOtpController::class, 'show'])->name('otp');
    Route::post('/login/otp', [\App\Http\Controllers\Admin\AdminOtpController::class, 'send'])->name('otp.send');
    Route::post('/login/otp/verify', [\App\Http\Controllers\Admin\AdminOtpController::class, 'verify'])->name('otp.verify');
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware(['admin'])->group(function () {
    Route::get('login-page', [\App\Http\Controllers\Admin\AdminLoginCmsController::class, 'index'])->name('login-page.index');
    Route::put('login-page', [\App\Http\Controllers\Admin\AdminLoginCmsController::class, 'update'])->name('login-page.update');
    Route::get('login-page/preview', [AdminAuthController::class, 'preview'])->name('login.preview');
    Route::get('about', [\App\Http\Controllers\Admin\AboutCmsController::class, 'index'])->name('about.index');
    Route::put('about', [\App\Http\Controllers\Admin\AboutCmsController::class, 'update'])->name('about.update');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile & Password
    Route::get('profile', [AdminProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [AdminProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

    // 1. Parking Locations (/admin/locations & /admin/parking-locations)
    Route::resource('locations', ParkingLocationController::class)->names('locations');
    Route::post('locations/{id}/toggle-status', [ParkingLocationController::class, 'toggleStatus'])->name('locations.toggle-status');
    Route::resource('parking-locations', ParkingLocationController::class);
    Route::post('parking-locations/{id}/toggle-status', [ParkingLocationController::class, 'toggleStatus'])->name('parking-locations.toggle-status');

    // 2. Parking Zones
    Route::resource('parking-zones', ParkingZoneController::class);

    // Dynamic Parking Services (Homepage & Facility Services)
    Route::resource('services', ParkingServiceController::class)->names('services');
    Route::resource('parking-services', ParkingServiceController::class);
    Route::post('services/{id}/toggle-status', [ParkingServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::post('services/page-settings', [ParkingServiceController::class, 'updatePageSettings'])->name('services.settings.update');
    Route::post('services/features', [ParkingServiceController::class, 'storeFeature'])->name('services.features.store');
    Route::put('services/features/{id}', [ParkingServiceController::class, 'updateFeature'])->name('services.features.update');
    Route::delete('services/features/{id}', [ParkingServiceController::class, 'destroyFeature'])->name('services.features.destroy');
    Route::post('parking-services/{id}/toggle-status', [ParkingServiceController::class, 'toggleStatus'])->name('parking-services.toggle-status');

    // 3. Parking Slots & Slot Status
    Route::get('parking-slots', [ParkingSlotController::class, 'index'])->name('parking-slots.index');
    Route::post('parking-slots', [ParkingSlotController::class, 'store'])->name('parking-slots.store');
    Route::put('parking-slots/{id}', [ParkingSlotController::class, 'update'])->name('parking-slots.update');
    Route::post('parking-slots/batch', [ParkingSlotController::class, 'batchStore'])->name('parking-slots.batch');
    Route::post('parking-slots/batch-store', [ParkingSlotController::class, 'batchStore'])->name('parking-slots.batch-store');
    Route::match(['post', 'patch'], 'parking-slots/{id}/status', [ParkingSlotController::class, 'updateStatus'])->name('parking-slots.status');
    Route::match(['post', 'patch'], 'parking-slots/{id}/update-status', [ParkingSlotController::class, 'updateStatus'])->name('parking-slots.update-status');
    Route::delete('parking-slots/{id}', [ParkingSlotController::class, 'destroy'])->name('parking-slots.destroy');

    // 4. Operations: Bookings
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // 5. Operations: Vehicle Entry (Requirement #29)
    Route::get('entries', [EntryController::class, 'index'])->name('entries.index');
    Route::post('entries/confirm', [EntryController::class, 'confirm'])->name('entries.confirm');

    // 6. Operations: Vehicle Exit (Requirement #31)
    Route::get('exits', [ExitController::class, 'index'])->name('exits.index');
    Route::post('exits/confirm', [ExitController::class, 'confirm'])->name('exits.confirm');

    // 7. Operations: Live Parking Availability (Requirement #33)
    Route::get('availability', [AvailabilityController::class, 'index'])->name('availability');
    Route::get('availability/monitor', [AvailabilityController::class, 'index'])->name('availability.index');

    // 8. Operations: Payments & Transactions (Requirement #35)
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('transactions', [PaymentController::class, 'transactions'])->name('transactions.index');
    Route::get('payments/transactions', [PaymentController::class, 'transactions'])->name('payments.transactions');

    // 9. Management: Users (Requirement #39)
    Route::resource('users', UserController::class);
    Route::match(['post', 'patch'], 'users/{id}/status', [UserController::class, 'updateStatus'])->name('users.status');

    // 10. Management: Vehicles
    Route::get('vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::post('vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::put('vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

    // 11. Management: Staff (Requirement #40)
    Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
    Route::put('staff/{id}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('staff/{id}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::post('staff/{id}/toggle-status', [StaffController::class, 'toggleStatus'])->name('staff.toggle-status');

    // 12. Reports & Intelligence: Analytics (Requirement #41)
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('analytics/view', [AnalyticsController::class, 'index'])->name('analytics.index');

    // 13. Reports & Intelligence: AI Insights (Requirement #42)
    Route::get('ai-insights', [AiInsightsController::class, 'index'])->name('ai-insights');
    Route::get('ai-insights/view', [AiInsightsController::class, 'index'])->name('ai-insights.index');

    // 14. Reports (Requirement #43)
    Route::get('reports', [ReportController::class, 'index'])->name('reports');
    Route::get('reports/view', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/occupancy', [ReportController::class, 'occupancy'])->name('reports.occupancy');
    Route::get('reports/parking', [ReportController::class, 'parking'])->name('reports.parking');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // 15. System: Settings (Requirement #45) & Homepage CMS
    Route::get('homepage', [HomepageCmsController::class, 'index'])->name('homepage.index');
    Route::post('homepage', [HomepageCmsController::class, 'update'])->name('homepage.update');

    // 15b. How It Works CMS
    Route::get('how-it-works', [HowItWorksCmsController::class, 'index'])->name('how-it-works.index');
    Route::post('how-it-works/settings', [HowItWorksCmsController::class, 'updateSettings'])->name('how-it-works.settings.update');
    Route::post('how-it-works/steps', [HowItWorksCmsController::class, 'storeStep'])->name('how-it-works.steps.store');
    Route::put('how-it-works/steps/{id}', [HowItWorksCmsController::class, 'updateStep'])->name('how-it-works.steps.update');
    Route::delete('how-it-works/steps/{id}', [HowItWorksCmsController::class, 'destroyStep'])->name('how-it-works.steps.destroy');
    Route::post('how-it-works/cta', [HowItWorksCmsController::class, 'updateCta'])->name('how-it-works.cta.update');
    Route::post('how-it-works/cta-features', [HowItWorksCmsController::class, 'storeCtaFeature'])->name('how-it-works.cta-features.store');
    Route::put('how-it-works/cta-features/{id}', [HowItWorksCmsController::class, 'updateCtaFeature'])->name('how-it-works.cta-features.update');
    Route::delete('how-it-works/cta-features/{id}', [HowItWorksCmsController::class, 'destroyCtaFeature'])->name('how-it-works.cta-features.destroy');
    Route::post('how-it-works/faqs', [HowItWorksCmsController::class, 'storeFaq'])->name('how-it-works.faqs.store');
    Route::put('how-it-works/faqs/{id}', [HowItWorksCmsController::class, 'updateFaq'])->name('how-it-works.faqs.update');
    Route::delete('how-it-works/faqs/{id}', [HowItWorksCmsController::class, 'destroyFaq'])->name('how-it-works.faqs.destroy');

    Route::get('settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('settings/view', [SettingsController::class, 'index'])->name('settings.index');
    Route::match(['post', 'put'], 'settings', [SettingsController::class, 'update'])->name('settings.update');
});
