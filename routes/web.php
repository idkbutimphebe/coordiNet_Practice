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
use App\Models\Event; // Add this at the top

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
        // List all bookings (Admin)
        Route::get('/', [AdminController::class, 'index'])->name('bookings');

        // Show single booking (Admin)
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
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/pending', [AdminController::class, 'pending'])->name('pending');
    Route::patch('/coordinator/{id}/approve', [AdminController::class, 'approveCoordinator'])->name('approve');
    Route::delete('/coordinator/{id}/decline', [AdminController::class, 'declineCoordinator'])->name('decline');
});


// Route::get('/pending', [AdminController::class, 'pending'])->name('pending.coordinator');
// Route::patch('/coordinator/{id}/approve', [AdminController::class, 'approveCoordinator'])->name('pending.coordinator.approve');
// Route::delete('/coordinator/{id}/decline', [AdminController::class, 'declineCoordinator'])->name('pending.coordinator.decline');


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

        // ================= SCHEDULE ROUTES =================
        Route::get('/schedule', [CoordinatorController::class, 'schedule'])->name('schedule');
        Route::get('/schedule-events', [CoordinatorController::class, 'getScheduleEvents'])->name('events');
        Route::post('/schedule/save', [CoordinatorController::class, 'saveEvent'])->name('schedule.save');

        // Ratings
        Route::get('/ratings', [CoordinatorController::class, 'reviews'])->name('ratings');

        // Income & Subscription (Static Views)
        Route::get('/income', fn () => view('coordinator.income'))->name('income');
        Route::get('/subscription', fn () => view('coordinator.subscription'))->name('subscription');

        // Profile
        Route::get('/profile', [CoordinatorController::class, 'profile'])->name('profile');
        Route::put('/profile', [CoordinatorController::class, 'updateProfile'])->name('update');
        
        //choose events
        Route::post('/events', [CoordinatorController::class, 'storeEvent'])
            ->name('coordinator.events.store');
            
          Route::post('/events', [CoordinatorController::class, 'storeEvent'])
            ->name('events.store');   

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

        // ✅ CLIENT DASHBOARD
        Route::get('/dashboard', [ClientController::class, 'dashboard'])
            ->name('dashboard');

        // ✅ COORDINATORS LIST


Route::get('/coordinators', [ClientController::class, 'coordinators'])
    ->name('coordinators');

        // ✅ VIEW SINGLE COORDINATOR
        Route::get('/coordinators/{id}', function ($id) {
            $coordinator = \App\Models\User::where('role', 'coordinator')->findOrFail($id);
            return view('client.coordinator-view', compact('coordinator'));
        })->name('coordinators.view');

        // ✅ CLIENT BOOKINGS
        Route::get('/bookings', [ClientController::class, 'bookings'])
            ->name('bookings.index');

        Route::get('/bookings/{booking}', [ClientController::class, 'showBooking'])
            ->name('bookings.show');

        Route::post('/bookings', [ClientController::class, 'storeBooking'])
            ->name('bookings.store');

        // ✅ RATINGS
        Route::get('/ratings', [ClientController::class, 'ratings'])
            ->name('ratings');

        // ✅ CLIENT PROFILE
        Route::get('/profile', [ClientController::class, 'edit'])->name('profile'); 
        Route::post('/profile/update', [ClientController::class, 'updateProfile'])->name('profile.update');
    });


require __DIR__ . '/auth.php';