<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Coordinator\PaymentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
|
| Redirect users based on their role. Guests see the welcome page.
|
*/
// ROOT
Route::get('/', function () {
    if (!auth()->check()) {
        return view('welcome');
    }

    return match (auth()->user()->role) {
        'admin'       => redirect()->route('dashboard'),
        'coordinator' => redirect()->route('coordinator.dashboard'),
        'client'      => redirect()->route('client.dashboard'),
        default       => redirect('/login'),
    };
});

// AUTH ROUTES
Route::middleware(['auth'])->group(function () {

    // ADMIN DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

// BOOKINGS

Route::prefix('bookings')->group(function () {
    // List all bookings
    Route::get('/', [AdminController::class, 'index'])->name('bookings');

    // Show single booking using "show" page
    Route::get('/show/{id}', [AdminController::class, 'show'])->name('bookings.show');
});


    // PENDING COORDINATORS
    Route::get('/pending', [AdminController::class, 'pending'])->name('pending');
    Route::post('/pending/{id}/approve', [AdminController::class, 'approve'])->name('pending.approve');
    Route::post('/pending/{id}/decline', [AdminController::class, 'decline'])->name('pending.decline');

    // COORDINATORS LIST & SHOW
    Route::get('/coordinators', [AdminController::class, 'coordinators'])->name('coordinators');
    Route::get('/coordinators/{id}', [AdminController::class, 'showCoordinator'])->name('coordinators.show');

    // REPORTS
    Route::prefix('reports')->name('reports.')->group(function () {

        Route::get('/topcoordinators', [AdminController::class, 'topCoordinators'])
             ->name('topcoordinators');

        Route::get('/coordinators', [AdminController::class, 'allCoordinators'])
             ->name('coordinators');

        Route::get('/clients', [AdminController::class, 'clientReport'])
             ->name('clients');

        Route::get('/bookings', [AdminController::class, 'bookingReport'])
             ->name('bookings');

        Route::get('/income', [AdminController::class, 'incomeReport'])
             ->name('income');

        Route::get('/ratings', [AdminController::class, 'ratingReport'])
             ->name('ratings');
    }); // end reports group

});

/*
|--------------------------------------------------------------------------
| PROFILE (ALL AUTH USERS)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});



/*
|--------------------------------------------------------------------------
| 1. COORDINATORS LIST (PUBLIC/CLIENT VIEW)
|--------------------------------------------------------------------------
| Note: Ensure your CoordinatorController has 'index', 'byEvent', and 'show' methods
| if you want to use these routes.
*/
Route::middleware('auth')->prefix('coordinators')->group(function () {
    Route::get('/', [CoordinatorController::class, 'index'])->name('coordinators');
    Route::get('/{event}', [CoordinatorController::class, 'byEvent'])->name('coordinators.event');
    Route::get('/{event}/{id}', [CoordinatorController::class, 'show'])->name('coordinators.show');
});

/*
|--------------------------------------------------------------------------
| 2. GENERAL PAGES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Points to the 'pending' method in your AdminController
    Route::get('/pending', [App\Http\Controllers\AdminController::class, 'pending'])->name('pending');
});
/*
|--------------------------------------------------------------------------
| 3. COORDINATOR DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('coordinator')
    ->name('coordinator.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [CoordinatorController::class, 'dashboard'])->name('dashboard');

        // Bookings
        Route::get('/bookings', [CoordinatorController::class, 'bookings'])->name('bookings');
        Route::get('/bookings/{id}', [CoordinatorController::class, 'bookingsShow'])->name('bookings.show');

        // ================= SCHEDULE ROUTES (FIXED) =================
        // 1. Show the Calendar Page
        Route::get('/schedule', [CoordinatorController::class, 'schedule'])->name('schedule');
        
        // 2. Fetch Data for the Calendar (CRITICAL: This allows the calendar to load events)
        Route::get('/schedule-events', [CoordinatorController::class, 'getScheduleEvents'])->name('events');

        // 3. Save a new personal event (Manual add)
        Route::post('/schedule/save', [CoordinatorController::class, 'saveEvent'])->name('schedule.save');
        // ===========================================================

        // Ratings
        Route::get('/ratings', [CoordinatorController::class, 'reviews'])->name('ratings');

        // Income & Subscription (Static Views)
        Route::get('/income', fn () => view('coordinator.income'))->name('income');
        Route::get('/subscription', fn () => view('coordinator.subscription'))->name('subscription');

        // Profile
        Route::get('/profile', [CoordinatorController::class, 'profile'])->name('profile');
        Route::put('/profile', [CoordinatorController::class, 'updateProfile'])->name('update');

        // Payments
        Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
        Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');
});

/*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('client')
    ->name('client.')
    ->group(function () {

        Route::get('/dashboard', fn () =>
            view('client.dashboard')
        )->name('dashboard');

        Route::get('/coordinators', fn () =>
            view('client.coordinators')
        )->name('coordinators');

        Route::get('/coordinators/{name}', fn ($name) =>
            view('client.coordinator-view', compact('name'))
        )->name('coordinators.view');

        Route::get('/bookings', fn () =>
            view('client.booking.index')
        )->name('bookings.index');

        Route::get('/bookings/{id}', fn ($id) =>
            view('client.booking.show', compact('id'))
        )->name('bookings.show');

        Route::get('/ratings', fn () =>
            view('client.ratings')
        )->name('ratings');

        Route::post('/ratings', [RatingController::class, 'store'])
            ->name('ratings.store');

        Route::get('/profile', fn () =>
            view('client.profile')
        )->name('profile');
    });

    

require __DIR__ . '/auth.php';
