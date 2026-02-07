<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BookingController; 
use App\Models\Event; 

/*
|-------------------------------------------------------------------------- 
| ROOT / REDIRECT
|-------------------------------------------------------------------------- 
*/
Route::get('/', function () {
    if (!auth()->check()) {
        return view('welcome');
    }

    // Role-based redirect
    return match (auth()->user()->role) {
        'admin'       => redirect()->route('dashboard'),
        'coordinator' => redirect()->route('coordinator.dashboard'),
        'client'      => redirect()->route('client.dashboard'),
        default       => redirect('/login'),
    };
});

/*
|-------------------------------------------------------------------------- 
| PROFILE (ALL AUTH USERS)
|-------------------------------------------------------------------------- 
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|-------------------------------------------------------------------------- 
| ADMIN ROUTES
|-------------------------------------------------------------------------- 
| Security: Checks are handled in AdminController::__construct()
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // 1. DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // 2. BOOKINGS
    Route::prefix('bookings')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('bookings');
        Route::get('/show/{id}', [AdminController::class, 'show'])->name('bookings.show');
    });

    // 3. PENDING COORDINATORS
// Page Route
    Route::get('/pending', [AdminController::class, 'pending'])->name('pending');

    // Action Routes
    Route::post('/approve/{id}', [AdminController::class, 'approveCoordinator'])->name('approve');
    Route::post('/decline/{id}', [AdminController::class, 'declineCoordinator'])->name('decline');

});
    // 4. REPORTS
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/topcoordinators', [AdminController::class, 'topCoordinators'])->name('topcoordinators');
        Route::get('/coordinators', [AdminController::class, 'allCoordinators'])->name('coordinators');
        Route::get('/clients', [AdminController::class, 'clientReport'])->name('clients');
        Route::get('/bookings', [AdminController::class, 'bookingReport'])->name('bookings');
        Route::get('/income', [AdminController::class, 'incomeReport'])->name('income');
        Route::get('/payments', [AdminController::class, 'paymentReport'])->name('payments');
        Route::get('/ratings', [AdminController::class, 'ratingReport'])->name('ratings');
    });
    
    // 5. MANAGE COORDINATOR PROFILES
    // Allows admin to see/edit specific coordinator details
    Route::name('admin.')->group(function () {
        Route::get('/coordinators/{id}', [CoordinatorController::class, 'show'])->name('coordinators.show');
        Route::put('/coordinators/{id}', [CoordinatorController::class, 'update'])->name('coordinators.update');
    });

/*
|-------------------------------------------------------------------------- 
| COORDINATOR ROUTES
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth'])
    ->prefix('coordinator')
    ->name('coordinator.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [CoordinatorController::class, 'dashboard'])->name('dashboard');

        // Bookings
        Route::get('/bookings', [CoordinatorController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{id}', [CoordinatorController::class, 'bookingsShow'])->name('bookings.show');
        Route::post('/bookings/{id}/confirm', [CoordinatorController::class, 'confirmBooking'])->name('bookings.confirm');
        Route::post('/bookings/{id}/cancel', [CoordinatorController::class, 'cancelBooking'])->name('bookings.cancel');

        // Schedule
        Route::get('/schedule', [CoordinatorController::class, 'schedule'])->name('schedule');
        Route::get('/schedule-events', [CoordinatorController::class, 'getScheduleEvents'])->name('events');
        Route::post('/schedule/save', [CoordinatorController::class, 'saveEvent'])->name('schedule.save');

        // Ratings
        Route::get('/ratings', [CoordinatorController::class, 'reviews'])->name('ratings');

        Route::get('/income', [CoordinatorController::class, 'income'])->name('income');

        Route::post('/payments', [CoordinatorController::class, 'storePayment'])->name('payments.store');

        Route::get('/ratings', [CoordinatorController::class, 'ratings'])->name('ratings');

        // Profile
        Route::get('/profile', [CoordinatorController::class, 'profile'])->name('profile');
        Route::put('/profile', [CoordinatorController::class, 'updateProfile'])->name('update');

        // Events
        Route::post('/events', [CoordinatorController::class, 'storeEvent'])->name('events.store'); 

        // Reports Sub-routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/clients', [CoordinatorController::class, 'reportClients'])->name('clients');
            Route::get('/bookings', [CoordinatorController::class, 'reportBookings'])->name('bookings');
            Route::get('/income', [CoordinatorController::class, 'reportIncome'])->name('income');
            Route::get('/feedback', [CoordinatorController::class, 'reportFeedback'])->name('feedback');
            Route::get('/payment-breakdown', [CoordinatorController::class, 'reportPaymentBreakdown'])->name('payment-breakdown');

            Route::get('/diagnostics', [CoordinatorController::class, 'diagnostics'])->name('diagnostics');
        });
    });
/*
|-------------------------------------------------------------------------- 
| CLIENT ROUTES
|-------------------------------------------------------------------------- 
*/
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

    // Coordinators List
    Route::get('/coordinators', [ClientController::class, 'coordinators'])->name('coordinators');
    Route::get('/coordinators/{id}', [ClientController::class, 'viewCoordinator'])->name('coordinators.view');

    // Bookings
    Route::get('/bookings', [ClientController::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [ClientController::class, 'showBooking'])->name('bookings.show');
    Route::post('/bookings', [ClientController::class, 'storeBooking'])->name('bookings.store');
    // ADD THIS LINE:
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');

    // Ratings
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::get('/ratings', [ClientController::class, 'ratings'])->name('ratings');

    // Payment Breakdown (New)
    Route::get('/bookings/{booking}/payments', [ClientController::class, 'bookingPaymentBreakdown'])->name('bookings.payments');
    Route::get('/reports/payment-breakdown', [ClientController::class, 'reportPaymentBreakdown'])->name('reports.payment-breakdown');

    // Profile
    Route::get('/profile', [ClientController::class, 'edit'])->name('profile');
    Route::put('/profile/update', [ClientController::class, 'updateProfile'])->name('profile.update');
});

/*
|-------------------------------------------------------------------------- 
| PUBLIC BROWSING (For Clients/Users to find Coordinators)
|-------------------------------------------------------------------------- 
*/
Route::middleware('auth')->prefix('coordinators')->group(function () {
    
    // List all
    Route::get('/', [CoordinatorController::class, 'index'])->name('coordinators');

    // Filter by event
    Route::get('/event/{event}', [CoordinatorController::class, 'byEvent'])->name('coordinators.event');

    // Show single profile (Public View)
    Route::get('/{id}', [CoordinatorController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('coordinators.show');
});

require __DIR__ . '/auth.php';