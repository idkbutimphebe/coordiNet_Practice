<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Coordinator;
use App\Models\Client;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard with stats and monthly booking availability.
     */
    public function dashboard()
    {
        $totalCoordinators = Coordinator::count();
        $totalBookings     = Booking::count();
        $pendingRequests   = Coordinator::where('status', 'pending')->count();

        $stats = [
            [
                'label' => 'Total Coordinators',
                'value' => number_format($totalCoordinators),
                'icon'  => 'users',
                'route' => route('reports.coordinators'),
            ],
            [
                'label' => 'Total Bookings',
                'value' => number_format($totalBookings),
                'icon'  => 'calendar',
                'route' => route('reports.bookings'),
            ],
            [
                'label' => 'Pending Requests',
                'value' => number_format($pendingRequests),
                'icon'  => 'clock',
                'route' => route('pending'),
            ],
        ];

        // Monthly booking availability
        $availability = [];
        $now = Carbon::now();
        $monthlyBookings = Booking::whereMonth('event_date', $now->month)
                          ->whereYear('event_date', $now->year)
                          ->get();

        for ($day = 1; $day <= $now->daysInMonth; $day++) {
            $isBooked = $monthlyBookings->contains(function ($booking) use ($day) {
                return Carbon::parse($booking->event_date)->day == $day;
        });

            $availability[$day] = $isBooked ? 'Booked' : 'Available';
        }

        return view('dashboard', compact('stats', 'availability'));
    }

    /**
     * Show all pending coordinators.
     */
    // public function pending()
    // {
    //     $pendingCoordinators = Coordinator::where('status', 'pending')
    //                                       ->orderBy('coordinator_name')
    //                                       ->paginate(10);

    //     return view('pending', compact('pendingCoordinators'));
    // }
public function approveCoordinator($id)
{
    $coordinator = User::findOrFail($id);
    $coordinator->is_active = 1;
    $coordinator->save();

    return redirect()->back()->with('success', 'Coordinator approved successfully.');
}

public function declineCoordinator($id)
{
    $coordinator = User::findOrFail($id);
    $coordinator->delete(); // or set a flag if you want to keep record

    return redirect()->back()->with('success', 'Coordinator declined.');
}


    /**
     * Show all coordinators report.
     */
public function allCoordinators()
{
    // Fetch all coordinators and count completed bookings directly from bookings table
    $coordinators = Coordinator::withCount([
        'bookings as bookings_count' => function ($query) {
            $query->where('status', 'completed');
        }
    ])->orderBy('coordinator_name')->get();

    return view('reports.coordinators', compact('coordinators'));
}


    /**
     * Top 10 coordinators report.
     */
    public function topCoordinators()
    {
        $topCoordinators = Coordinator::with('events.bookings')
            ->get()
            ->map(function ($coordinator) {
                $completedBookings = $coordinator->events->sum(function ($event) {
                    return $event->bookings->where('status', 'completed')->count();
                });

                $ratings = $coordinator->events->flatMap(function ($event) {
                    return $event->bookings->pluck('rating')->filter();
                });

                $averageRating = $ratings->count() ? round($ratings->avg(), 1) : null;

                return [
                    'coordinator' => $coordinator,
                    'bookings_count' => $completedBookings,
                    'ratings_avg' => $averageRating,
                ];
            })
            ->sortByDesc('bookings_count')
            ->take(10);

        return view('reports.topcoordinators', compact('topCoordinators'));
    }

    /**
     * Clients report.
     */
    public function clientReport()
    {
        $clients = Client::with(['event.coordinator'])
                         ->orderBy('name')
                         ->paginate(10);

        return view('reports.clients', compact('clients'));
    }

    /**
     * Bookings report.
     */
    public function bookingReport()
    {
        $bookings = Booking::with(['client', 'event.coordinator'])
                           ->orderBy('booking_date', 'desc')
                           ->paginate(10);

        return view('reports.bookings', compact('bookings'));
    }

    /**
     * Income report (total payments per coordinator).
     */
    public function incomeReport()
    {
        $coordinators = Coordinator::with(['events.bookings'])
            ->get()
            ->map(function ($coordinator) {
                $totalIncome = $coordinator->events->sum(function ($event) {
                    return $event->bookings->sum('total_amount');
                });

                return [
                    'coordinator' => $coordinator,
                    'total_income' => $totalIncome,
                ];
            });

        return view('reports.income', compact('coordinators'));
    }

    /**
     * Ratings report.
     */
    public function ratingReport()
    {
        $ratings = Booking::with(['client', 'event', 'coordinator'])
            ->latest()
            ->get();

        return view('reports.ratings', compact('ratings'));
    }

    /**
     * Bookings list for admin index page (dynamic with search).
     */
public function index(Request $request)
{
    $query = Booking::with(['client', 'event.coordinator']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->whereHas('client', fn($q) => $q->where('name', 'like', "%$search%"))
              ->orWhereHas('event', fn($q) => $q->where('name', 'like', "%$search%"));
    }

    $bookings = $query->orderBy('event_date', 'desc') // <-- fixed
                      ->paginate(10);

    return view('bookings.index', compact('bookings'));
}

public function show($id)
{
    // Load the booking with client and event info
    $booking = Booking::with(['client', 'event'])->findOrFail($id);

    return view('bookings.show', compact('booking'));
}

public function pending()
{
    $pendingCoordinators = \App\Models\User::where('role', 'coordinator')
        ->where('is_active', 0)
        ->paginate(10); // <- pagination added

    return view('pending', compact('pendingCoordinators'));
}

public function __construct()
{
    $this->middleware(function ($request, $next) {
        if (!auth()->check() || strtolower(auth()->user()->role) !== 'admin') {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    });
}





}

