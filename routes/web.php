<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Coordinator\PaymentController;
use App\Http\Controllers\RatingController;
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
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| BOOKINGS (ADMIN)
|--------------------------------------------------------------------------
*/
// Route::middleware('auth')->group(function () {

//     // Route::get('/bookings', fn () =>
//     //     view('bookings.index')
//     // )->name('bookings');

//     // Route::get('/bookings/{id}', fn ($id) =>
//     //     view('bookings.show', compact('id'))
//     // )->name('bookings.show');

// });

/*
|--------------------------------------------------------------------------
| REPORTS (ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('reports')->group(function () {

    Route::get('/', [ReportController::class, 'index'])
        ->name('reports');

    Route::get('/top-coordinators', [ReportController::class, 'topCoordinators'])
        ->name('reports.topcoordinators');

    Route::get('/coordinators', [ReportController::class, 'coordinators'])
        ->name('reports.coordinators');

    Route::get('/clients', [ReportController::class, 'clients'])
        ->name('reports.clients');

    Route::get('/bookings', [ReportController::class, 'bookings'])
        ->name('reports.bookings');

    Route::get('/income', [ReportController::class, 'income'])
        ->name('reports.income');

    Route::get('/ratings', [ReportController::class, 'ratings'])
        ->name('reports.ratings');
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
| COORDINATORS (ADMIN VIEW)
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
| COORDINATOR
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->prefix('coordinator')
    ->name('coordinator.')
    ->group(function () {

        Route::get('/dashboard', fn () =>
            view('coordinator.dashboard')
        )->name('dashboard');

        Route::get('/bookings', [CoordinatorController::class, 'bookings'])
        ->name('bookings');


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

        Route::get('/checkout', [PaymentController::class, 'checkout'])
            ->name('checkout');

        Route::post('/pay', [PaymentController::class, 'pay'])
            ->name('pay');
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

        // Dashboard
        Route::get('/dashboard', [ClientDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Coordinators
        Route::get('/coordinators', [ClientDashboardController::class, 'coordinatorsPage'])
            ->name('coordinators');

        Route::get('/coordinators/{coordinator}', [CoordinatorController::class, 'showForClient'])
            ->name('coordinators.profile');

        Route::post('/coordinators/{coordinator}/book', [ClientDashboardController::class, 'bookCoordinator'])
            ->name('coordinators.book');

        // Bookings
        Route::get('/bookings', [ClientDashboardController::class, 'myBookings'])
            ->name('bookings.index'); // group prefix 'client.' + 'bookings.index' = client.bookings.index



        // Ratings
        Route::get('/ratings', fn () => view('client.ratings'))
            ->name('ratings');
        Route::post('/ratings', [RatingController::class, 'store'])
            ->name('ratings.store');

        // Profile
        Route::get('/profile', fn () => view('client.profile'))
            ->name('profile');
    });

require __DIR__ . '/auth.php';
