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
use App\Http\Controllers\ClientDashboardController;


/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
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
// Registration
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);

// Login
Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);

// Dashboard (role-based)
Route::middleware(['auth', 'role:client'])->group(function() {
    Route::get('/client/dashboard', [ClientDashboardController::class, 'index'])->name('client.dashboard');
});

Route::middleware(['auth', 'role:coordinator'])->group(function() {
    Route::get('/coordinator/dashboard', [CoordinatorDashboardController::class, 'index'])->name('coordinator.dashboard');
});

// AUTH ROUTES
Route::middleware(['auth'])->group(function () {

    // ADMIN DASHBOARD
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});


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

    Route::get('/', [CoordinatorController::class, 'index'])
        ->name('coordinators');

    Route::get('/{event}', [CoordinatorController::class, 'byEvent'])
        ->name('coordinators.event');

    Route::get('/{event}/{id}', [CoordinatorController::class, 'show'])
        ->name('coordinators.show');

});
Route::middleware('auth')->group(function () {

    Route::get('/pendng', fn () =>
        view('pending')
    )->name('pending');
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

        Route::get('/dashboard', fn () =>
            view('coordinator.dashboard')
        )->name('dashboard');

        Route::get('/bookings', fn () =>
            view('coordinator.bookings')
        )->name('bookings');

        Route::get('/bookings/{id}', fn ($id) =>
            view('coordinator.bookings-show')
        )->name('bookings.show');

        Route::get('/schedule', fn () =>
            view('coordinator.schedule')
        )->name('schedule');

        Route::get('/ratings', fn () =>
            view('coordinator.ratings')
        )->name('ratings');

        Route::get('/income', fn () =>
            view('coordinator.income')
        )->name('income');

        Route::get('/subscription', fn () =>
            view('coordinator.subscription')
        )->name('subscription');

        Route::get('/profile', fn () =>
            view('coordinator.profile')
        )->name('profile');

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

        // Dashboard (already exists, kept as-is)
        Route::get('/dashboard', fn () => view('client.dashboard'))->name('dashboard');

        // ------------------ MISSING ROUTES ADDED ------------------

        // Coordinators via controller
        Route::get('/coordinators', [ClientDashboardController::class, 'coordinatorsPage'])->name('coordinators');
        Route::get('/coordinators/{coordinator}', [CoordinatorController::class, 'showForClient'])->name('coordinators.profile');

        // Bookings via controller
        Route::get('/bookings', [ClientDashboardController::class, 'myBookings'])->name('bookings.index');
        Route::get('/bookings/{id}', [ClientDashboardController::class, 'bookingShow'])->name('bookings.show');

        // Book a coordinator
        Route::post('/coordinators/{coordinator}/book', [ClientDashboardController::class, 'bookCoordinator'])->name('coordinators.book');

        // Ratings
        Route::get('/ratings', [ClientDashboardController::class, 'ratingsPage'])->name('ratings');
        Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

        // Profile via controller
        Route::get('/profile', [ClientDashboardController::class, 'profile'])->name('profile');

        // ------------------ END MISSING ROUTES ------------------
    });

require __DIR__ . '/auth.php';
