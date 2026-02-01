<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
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
        $monthlyBookings = Booking::whereMonth('booking_date', $now->month)
                                  ->whereYear('booking_date', $now->year)
                                  ->get();

        for ($day = 1; $day <= $now->daysInMonth; $day++) {
            $isBooked = $monthlyBookings->contains(function ($booking) use ($day) {
                return Carbon::parse($booking->booking_date)->day == $day;
            });
            $availability[$day] = $isBooked ? 'Booked' : 'Available';
        }

        return view('dashboard', compact('stats', 'availability'));
    }

    /**
     * Show all pending coordinators.
     */
    public function pending()
    {
        $pendingCoordinators = Coordinator::where('status', 'pending')
                                          ->orderBy('coordinator_name')
                                          ->paginate(10);

        return view('pending', compact('pendingCoordinators'));
    }

    /**
     * Approve a pending coordinator.
     */
    public function approve($id)
    {
        $coordinator = Coordinator::findOrFail($id);
        $coordinator->status = 'approved';
        $coordinator->save();

        return redirect()->route('pending')->with('success', 'Coordinator approved successfully.');
    }

    /**
     * Decline a pending coordinator.
     */
    public function decline($id)
    {
        $coordinator = Coordinator::findOrFail($id);
        $coordinator->status = 'declined';
        $coordinator->save();

        return redirect()->route('pending')->with('success', 'Coordinator declined.');
    }

    /**
     * Show all coordinators report.
     */
    public function allCoordinators()
    {
        $coordinators = Coordinator::withCount(['events as bookings_count' => function ($query) {
            $query->whereHas('bookings', function ($q) {
                $q->where('status', 'completed');
            });
        }])->orderBy('coordinator_name')->get();

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

        $bookings = $query->orderBy('booking_date', 'desc')
                          ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show single booking details page (dynamic).
     */
/**
 * Show a single booking details.
 */
public function show($id)
{
    $booking = Booking::with(['client', 'event.coordinator'])->findOrFail($id);
    return view('bookings.show', compact('booking'));
}

}
