<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoordinatorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Coordinator\PaymentController;
use App\Http\Controllers\RatingController;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
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
Route::middleware('auth')->group(function () {

    Route::get('/bookings', fn () => view('bookings.index'))->name('bookings');
    Route::get('/bookings/{id}', fn ($id) =>
        view('bookings.show', compact('id'))
    )->name('bookings.show');

});

/*
|--------------------------------------------------------------------------
| REPORTS (ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('reports')->group(function () {

    Route::get('/', [ReportController::class, 'index'])->name('reports');
    Route::get('/coordinators', [ReportController::class, 'coordinators'])->name('reports.coordinators');
    Route::get('/clients', [ReportController::class, 'clients'])->name('reports.clients');
    Route::get('/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('/income', [ReportController::class, 'income'])->name('reports.income');
    Route::get('/ratings', [ReportController::class, 'ratings'])->name('reports.ratings');

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
| COORDINATORS (ADMIN VIEW)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('coordinators')->group(function () {

    Route::get('/', [CoordinatorController::class, 'index'])->name('coordinators');
    Route::get('/{event}', [CoordinatorController::class, 'byEvent'])->name('coordinators.event');
    Route::get('/{event}/{id}', [CoordinatorController::class, 'show'])->name('coordinators.show');

});

/*
|--------------------------------------------------------------------------
| COORDINATOR 
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('coordinator')
    ->name('coordinator.')
    ->group(function () {

        // ================= DASHBOARD PAGES =================
        Route::get('/dashboard', fn () => view('coordinator.dashboard'))
            ->name('dashboard');

        Route::get('/bookings', fn () => view('coordinator.bookings'))
            ->name('bookings');

        Route::get('/bookings/{id}', fn ($id) => view('coordinator.bookings-show'))
            ->name('bookings.show');

        Route::get('/schedule', fn () => view('coordinator.schedule'))
            ->name('schedule');

        Route::get('/ratings', fn () => view('coordinator.ratings'))
            ->name('ratings');

        Route::get('/income', fn () => view('coordinator.income'))
            ->name('income');

        Route::get('/subscription', fn () => view('coordinator.subscription'))
            ->name('subscription');

        Route::get('/profile', fn () => view('coordinator.profile'))
            ->name('profile');

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


require __DIR__.'/auth.php';
