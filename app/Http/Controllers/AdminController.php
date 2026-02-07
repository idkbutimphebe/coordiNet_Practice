<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Coordinator;
use App\Models\Client;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AdminController extends Controller
{
    // ----------------------------------------------------------------
    // DASHBOARD
    // ----------------------------------------------------------------
    public function dashboard()
    {
        $totalCoordinators = Coordinator::count();
        $totalBookings     = Booking::count();
        
        // Count users who are coordinators but NOT active yet (Pending)
        $pendingRequests   = User::where('role', 'coordinator')
                                 ->where('is_active', 0)
                                 ->count();

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

    // ----------------------------------------------------------------
    // COORDINATOR APPROVAL LOGIC
    // ----------------------------------------------------------------

    /**
     * Show pending coordinator requests.
     * Looks for Users with role='coordinator' and is_active=0
     */
    public function pending(Request $request)
    {
        // Get users who are coordinators but NOT active yet
        $query = User::where('role', 'coordinator')->where('is_active', 0);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        // Fetch results (9 per page)
        $pendingCoordinators = $query->orderBy('created_at', 'desc')->paginate(9);

        // Append search query to pagination links
        $pendingCoordinators->appends($request->all());

        return view('pending', compact('pendingCoordinators'));
    }

    // Approve logic
    public function approveCoordinator($id)
    {
        $user = User::findOrFail($id);
        
        // 1. Activate the User login
        $user->is_active = 1;
        $user->save();

        // 2. Create the Coordinator Profile row if it doesn't exist
        if (!$user->coordinator) {
            Coordinator::create([
                'user_id' => $user->id,
                'coordinator_name' => $user->name,
                'status' => 'approved',
                'expertise' => 'General', // Default placeholder
                'phone_number' => '',
                'address' => '',
            ]);
        } else {
            $user->coordinator->update(['status' => 'approved']);
        }

        return redirect()->back()->with('success', 'Coordinator approved successfully.');
    }

    // Decline logic
    public function declineCoordinator($id)
    {
        $user = User::findOrFail($id);
        
        // Delete the user account
        $user->delete(); 

        return redirect()->back()->with('success', 'Coordinator request declined.');
    }


    // ----------------------------------------------------------------
    // REPORTS & LISTS WITH FILTERS
    // ----------------------------------------------------------------
    
    public function allCoordinators(Request $request)
    {
        $query = Coordinator::withCount([
            'bookings as bookings_count' => function ($query) {
                $query->where('status', 'completed');
            }
        ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('coordinator_name', 'like', "%$search%")
                  ->orWhere('phone_number', 'like', "%$search%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('email', 'like', "%$search%");
                  });
            });
        }

        $coordinators = $query->orderBy('coordinator_name')->get();

        return view('reports.coordinators', compact('coordinators'));
    }

    public function topCoordinators(Request $request)
    {
        $query = Coordinator::with('events.bookings');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('coordinator_name', 'like', "%$search%");
        }

        $topCoordinators = $query->get()
            ->map(function ($coordinator) {
                // 1. Calculate Completed Bookings
                $completedBookings = $coordinator->events->sum(function ($event) {
                    return $event->bookings->where('status', 'completed')->count();
                });

                // 2. Calculate Average Rating
                $ratings = $coordinator->events->flatMap(function ($event) {
                    return $event->bookings->pluck('rating')->filter();
                });

                $averageRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : 0;

                return [
                    'coordinator' => $coordinator,
                    'bookings_count' => $completedBookings,
                    'ratings_avg' => $averageRating,
                ];
            })
            ->sortBy([
                ['ratings_avg', 'desc'],
                ['bookings_count', 'desc'],
            ])
            ->take(10);

        return view('reports.topcoordinators', compact('topCoordinators'));
    }

    public function clientReport(Request $request)
    {
        $query = Booking::with(['client', 'event', 'coordinator']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', fn($c) => $c->where('name', 'like', "%$search%"))
                  ->orWhereHas('event', fn($e) => $e->where('event_name', 'like', "%$search%"))
                  ->orWhereHas('coordinator', fn($co) => $co->where('coordinator_name', 'like', "%$search%"))
                  ->orWhere('event_name', 'like', "%$search%"); // Also search in bookings.event_name column
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(10);

        return view('reports.clients', compact('bookings'));
    }

    public function bookingReport(Request $request)
    {
        $query = Booking::with(['client', 'event.coordinator']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', fn($c) => $c->where('name', 'like', "%$search%"))
                  ->orWhereHas('event', fn($e) => $e->where('event_name', 'like', "%$search%"))
                  ->orWhereHas('event.coordinator', fn($co) => $co->where('coordinator_name', 'like', "%$search%"))
                  ->orWhere('event_name', 'like', "%$search%"); // Also search in bookings.event_name column
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('event_date', 'desc')->paginate(10);

        return view('reports.bookings', compact('bookings'));
    }

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

    public function ratingReport(Request $request)
    {
        $query = Booking::with(['client', 'event', 'coordinator']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', fn($c) => $c->where('name', 'like', "%$search%"))
                  ->orWhereHas('event', fn($e) => $e->where('event_name', 'like', "%$search%"))
                  ->orWhereHas('coordinator', fn($co) => $co->where('coordinator_name', 'like', "%$search%"));
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $ratings = $query->latest()->get();

        return view('reports.ratings', compact('ratings'));
    }

    public function index(Request $request)
    {
        $query = Booking::with(['client', 'event.coordinator']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('client', fn($q) => $q->where('name', 'like', "%$search%"))
                  ->orWhereHas('event', fn($q) => $q->where('name', 'like', "%$search%"));
        }

        $bookings = $query->orderBy('event_date', 'desc')->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['client', 'event'])->findOrFail($id);
        return view('bookings.show', compact('booking'));
    }

    public function coordinators(Request $request)
    {
        // Start the query and eager load relationships (user, events) to prevent N+1 issues
        $query = Coordinator::with(['user', 'events']);

        // 1. Search Logic (Name or Address)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('address', 'like', "%{$search}%")
                  ->orWhere('coordinator_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Filter Logic (Event Type)
        if ($request->filled('event_type')) {
            $type = $request->event_type;
            $query->where(function ($q) use ($type) {
                $q->whereHas('events', function ($e) use ($type) {
                    $e->where('event_type', 'like', "%{$type}%");
                })
                ->orWhere('expertise', 'like', "%{$type}%");
            });
        }

        // Get results
        $rawCoordinators = $query->get();

        // 3. Grouping Logic
        $coordinators = $rawCoordinators->groupBy(function ($item) {
            return $item->events->first()->event_type 
                ?? $item->event_type 
                ?? $item->expertise 
                ?? 'General';
        });

        return view('admin.coordinators.index', compact('coordinators'));
    }
}