<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Coordinator;
use App\Models\Client;
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
// Display the pending list
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

        // FIXED: Changed 'admin.pending' to 'pending'
        // This assumes your file is saved as: resources/views/pending.blade.php
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
    // REPORTS & LISTS
    // ----------------------------------------------------------------
    public function allCoordinators()
    {
        $coordinators = Coordinator::withCount([
            'bookings as bookings_count' => function ($query) {
                $query->where('status', 'completed');
            }
        ])->orderBy('coordinator_name')->get();

        return view('reports.coordinators', compact('coordinators'));
    }

public function topCoordinators()
    {
        $topCoordinators = Coordinator::with('events.bookings')
            ->get()
            ->map(function ($coordinator) {
                // 1. Calculate Completed Bookings
                $completedBookings = $coordinator->events->sum(function ($event) {
                    return $event->bookings->where('status', 'completed')->count();
                });

                // 2. Calculate Average Rating
                // Gather all ratings from all bookings across all events
                $ratings = $coordinator->events->flatMap(function ($event) {
                    return $event->bookings->pluck('rating')->filter(); // Removes null/empty ratings
                });

                $averageRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : 0;

                return [
                    'coordinator' => $coordinator,
                    'bookings_count' => $completedBookings,
                    'ratings_avg' => $averageRating,
                ];
            })
            // === FIX IS HERE ===
            // Sort by Rating first (Highest to Lowest)
            // If ratings are equal, those with more bookings appear first
            ->sortBy([
                ['ratings_avg', 'desc'],
                ['bookings_count', 'desc'],
            ])
            ->take(10); // Take top 10

        return view('reports.topcoordinators', compact('topCoordinators'));
    }
public function clientReport()
    {
        // We fetch Bookings because they contain the Client, Event, and Coordinator data.
        // We eagerly load 'client', 'event', and 'coordinator' to avoid missing data.
        $bookings = Booking::with(['client', 'event', 'coordinator'])
                           ->latest() // Sorts by newest booking first
                           ->paginate(10);

        // We pass 'bookings' to the view instead of 'clients'
        return view('reports.clients', compact('bookings'));
    }

public function bookingReport()
    {
        // FIXED: Changed 'booking_date' to 'event_date'
        // If 'event_date' also fails, try using latest() to sort by creation time
        $bookings = Booking::with(['client', 'event.coordinator'])
                           ->orderBy('event_date', 'desc') 
                           ->paginate(10);

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

    public function ratingReport()
    {
        $ratings = Booking::with(['client', 'event', 'coordinator'])
            ->latest()
            ->get();

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
        // Filter coordinators who have an event of this type
        // OR whose expertise string matches the type
        $query->where(function ($q) use ($type) {
            $q->whereHas('events', function ($e) use ($type) {
                $e->where('event_type', 'like', "%{$type}%");
            })
            ->orWhere('expertise', 'like', "%{$type}%");
        });
    }

    // Get results
    $rawCoordinators = $query->get();

    // 3. Grouping Logic (Crucial for your View)
    // We group the collection by event_type to match your @foreach($coordinators as $event => $items)
    $coordinators = $rawCoordinators->groupBy(function ($item) {
        return $item->events->first()->event_type 
            ?? $item->event_type 
            ?? $item->expertise 
            ?? 'General';
    });

    return view('admin.coordinators.index', compact('coordinators'));
}
}