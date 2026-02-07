<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Event;
use App\Models\Coordinator;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CoordinatorController extends Controller
{
    // ========================================
    // HELPER METHODS
    // ========================================
    
    private function coordinatorIdOrNull(): ?int
    {
        $coordinator = Auth::user()?->coordinator;
        return $coordinator ? (int) $coordinator->id : null;
    }

    private function requireCoordinatorId(): int
    {
        $id = $this->coordinatorIdOrNull();
        if (!$id) abort(403, 'Coordinator profile not found.');
        return $id;
    }

    // ========================================
    // DASHBOARD
    // ========================================
    
    public function dashboard()
    {
        $user = Auth::user();

        // CHECK: If user is coordinator but has no profile row, create it now.
        if ($user->role === 'coordinator' && !$user->coordinator) {
            Coordinator::create([
                'user_id' => $user->id,
                'coordinator_name' => $user->name,
                'expertise' => '',
                'phone_number' => '',
                'address' => '',
                'status' => 'approved', 
            ]);
            $user->refresh();
        }

        $coordinatorId = $this->coordinatorIdOrNull();

        // Safety check
        if (!$coordinatorId) {
            return view('coordinator.dashboard', [
                'pendingBookings' => collect(),
                'stats' => [],
                'statusChart' => [],
                'activityLabels' => [],
                'activityData' => []
            ]);
        }

        $pendingBookings = Booking::where('coordinator_id', $coordinatorId)
            ->where('status', 'pending')
            ->with('client')
            ->get();
            
        $confirmedBookings = Booking::where('coordinator_id', $coordinatorId)
            ->where('status', 'confirmed')
            ->count();
            
        $upcomingEvents = Booking::where('coordinator_id', $coordinatorId)
            ->whereDate('event_date', '>=', now())
            ->count();

        $stats = [
            [
                'label' => 'Confirmed Bookings',
                'value' => $confirmedBookings,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>',
                'link' => route('coordinator.bookings', ['status' => 'confirmed'])
            ],
            [
                'label' => 'Pending Bookings',
                'value' => $pendingBookings->count(),
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'link' => route('coordinator.bookings', ['status' => 'pending'])
            ],
            [
                'label' => 'Upcoming Events',
                'value' => $upcomingEvents,
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                'link' => route('coordinator.schedule')
            ],
        ];

        $statusChart = [
            'completed' => $confirmedBookings,
            'pending' => $pendingBookings->count(),
            'cancelled' => Booking::where('coordinator_id', $coordinatorId)
                ->where('status', 'cancelled')
                ->count()
        ];

        $activityLabels = [];
        $activityData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $activityLabels[] = $date->format('D');
            $activityData[] = Booking::whereDate('event_date', $date)
                ->where('coordinator_id', $coordinatorId)
                ->count();
        }

        return view('coordinator.dashboard', compact(
            'pendingBookings',
            'stats',
            'statusChart',
            'activityLabels',
            'activityData'
        ));
    }

    // ========================================
    // BOOKINGS
    // ========================================
    
    public function bookings(Request $request)
    {
        $coordinatorId = $this->coordinatorIdOrNull();
        
        if (!$coordinatorId) {
            return redirect()->route('coordinator.profile')
                ->with('error', 'Coordinator profile not found. Please complete your profile first.');
        }

        $query = Booking::where('coordinator_id', $coordinatorId)
            ->with(['client', 'event']);
            
        // Status filter
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Search filter
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', fn($c) => $c->where('name', 'like', "%$search%"))
                  ->orWhere('event_name', 'like', "%$search%");
            });
        }

        $orderBy = $request->get('orderBy', 'event_date');
        $bookings = $query->orderBy($orderBy, 'desc')->paginate(10);
        
        return view('coordinator.bookings', compact('bookings'));
    }

    public function bookingsShow($id)
    {
        $coordinatorId = $this->requireCoordinatorId();
        
        $booking = Booking::with(['client', 'event', 'coordinator.user'])
            ->where('coordinator_id', $coordinatorId)
            ->findOrFail($id);
            
        return view('coordinator.bookings-show', compact('booking'));
    }

    public function updateBooking(Request $request, $id)
    {
        $coordinatorId = $this->requireCoordinatorId();
        
        $booking = Booking::where('coordinator_id', $coordinatorId)
            ->findOrFail($id);
            
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,paid'
        ]);
        
        $booking->status = $request->status;
        $booking->save();
        
        return back()->with('success', "Booking {$request->status} successfully!");
    }

    public function confirmBooking($id)
    {
        request()->merge(['status' => 'confirmed']);
        return $this->updateBooking(request(), $id);
    }

    public function cancelBooking($id)
    {
        request()->merge(['status' => 'cancelled']);
        return $this->updateBooking(request(), $id);
    }

    // ========================================
    // SCHEDULE
    // ========================================
    
    public function schedule(Request $request) 
    { 
        $coordinatorId = $this->requireCoordinatorId();

        // Determine Month (from URL or Current)
        $date = $request->has('month') 
            ? Carbon::parse($request->input('month')) 
            : Carbon::now();

        // Calculate Calendar Variables
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        // Days to skip before 1st of month (0=Sun, 1=Mon...)
        $emptySlots = $startOfMonth->dayOfWeek; 
        
        // Total days in month
        $daysInMonth = $date->daysInMonth;

        // Navigation Links
        $prevMonth = $date->copy()->subMonth()->format('Y-m-d');
        $nextMonth = $date->copy()->addMonth()->format('Y-m-d');

        // Fetch Busy Dates
        
        // Client Bookings
        $clientBookings = Booking::where('coordinator_id', $coordinatorId)
            ->whereIn('status', ['confirmed', 'paid', 'pending']) 
            ->whereBetween('event_date', [$startOfMonth, $endOfMonth])
            ->pluck('event_date')
            ->map(fn($d) => Carbon::parse($d)->format('j'))
            ->toArray();

        // Personal Schedules
        $personalSchedules = Schedule::where('coordinator_id', $coordinatorId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->format('j'))
            ->toArray();

        // Merge and clean
        $bookedDates = array_unique(array_merge($clientBookings, $personalSchedules));

        return view('coordinator.schedule', compact(
            'date', 
            'emptySlots', 
            'daysInMonth', 
            'prevMonth', 
            'nextMonth', 
            'bookedDates'
        ));
    }

    public function getScheduleEvents()
    {
        $events = []; 
        $coorId = $this->coordinatorIdOrNull();

        if ($coorId) {
            // Client Bookings
            $bookings = Booking::with('client')
                ->where('coordinator_id', $coorId)
                ->where('status', '!=', 'cancelled')
                ->get();
                
            foreach ($bookings as $b) {
                $events[] = [
                    'id' => 'booking-' . $b->id,
                    'title' => 'Client: ' . ($b->client->name ?? 'Booking'),
                    'start' => $b->booking_date,
                    'backgroundColor' => '#3E3F29',
                    'borderColor' => '#3E3F29',
                    'extendedProps' => [
                        'location' => $b->location ?? 'TBD',
                        'status' => ucfirst($b->status),
                        'type' => 'Client Booking'
                    ]
                ];
            }
            
            // Personal Schedules
            try {
                $schedules = Schedule::where('coordinator_id', $coorId)->get();
                
                foreach ($schedules as $s) {
                    $events[] = [
                        'id' => 'schedule-' . $s->id,
                        'title' => $s->name,
                        'start' => $s->date . 'T' . $s->start_time,
                        'end' => $s->date . 'T' . $s->end_time,
                        'backgroundColor' => '#A1BC98',
                        'borderColor' => '#A1BC98',
                        'extendedProps' => [
                            'location' => $s->location ?? '',
                            'type' => 'Personal Schedule'
                        ]
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Schedule table error: ' . $e->getMessage());
            }
        }

        return response()->json($events);
    }

    public function saveEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'nullable|string|max:255',
        ]);

        $coorId = $this->requireCoordinatorId();

        try {
            $event = Schedule::create([
                'coordinator_id' => $coorId, 
                'name' => $request->name,
                'date' => $request->date,
                'start_time' => Carbon::parse($request->start_time)->format('H:i:s'),
                'end_time' => Carbon::parse($request->end_time)->format('H:i:s'),
                'location' => $request->location
            ]);

            return response()->json(['success' => true, 'event' => $event]);
        } catch (\Exception $e) {
            Log::error('Save Event Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================
    // REVIEWS
    // ========================================
    
 public function ratings()
    {
        $coordinatorId = $this->requireCoordinatorId();

        // 1. Get all bookings for this coordinator that have reviews
        $ratings = Booking::where('coordinator_id', $coordinatorId)
            ->whereHas('reviews') // Only get bookings that actually have a review
            ->with(['client', 'event', 'reviews']) // Load the relationships
            ->latest() // Newest first
            ->get();

        // 2. Calculate Statistics
        $totalReviews = $ratings->count();
        
        // Calculate average. We extract the 'rating' from the 'reviews' relationship
        $avg = $totalReviews > 0 
            ? $ratings->avg(fn($booking) => $booking->reviews->rating) 
            : 0;
            
        $formattedAvg = number_format($avg, 1);

        // 3. Return the view with ALL the variables
        return view('coordinator.ratings', compact('ratings', 'totalReviews', 'formattedAvg'));
    }

    // ========================================
    // PAYMENTS & INCOME
    // ========================================
    
    /**
     * Show the income and payments ledger
     */
public function income(Request $request)
    {
        $coordinatorId = $this->requireCoordinatorId();

        // Get filtered payments
        $payments = $this->getFilteredPayments($coordinatorId, $request);

        // FIX: Direct query instead of calling missing function
        // This gets bookings that are pending or confirmed so they appear in your dropdown
        $pendingBookings = Booking::where('coordinator_id', $coordinatorId)
            ->whereIn('status', ['pending', 'confirmed']) 
            ->with(['client', 'event'])
            ->get();

        // Calculate statistics
        $stats = $this->calculatePaymentStats($coordinatorId, $request);

        // Get all confirmed bookings for the coordinator
        $bookings = Booking::with(['client', 'event', 'payments'])
            ->where('coordinator_id', $coordinatorId)
            ->whereIn('status', ['confirmed', 'paid'])
            ->latest()
            ->get();

        return view('coordinator.income', compact('pendingBookings', 'payments', 'stats', 'bookings'));
    }

    /**
     * Save a new payment from the modal
     */
public function store(Request $request)
    {
        // 1. Validate the input (Ensure reference_number is allowed)
        $validated = $request->validate([
            'booking_id'       => 'required|exists:bookings,id',
            'amount'           => 'required|numeric|min:0.01',
            'date_paid'        => 'required|date',
            'method'           => 'required|string',
            'reference_number' => 'nullable|text|max:255', // Validation added here
            'notes'            => 'nullable|text',
        ]);

        // 2. Create the payment
        \App\Models\Payment::create([
            'booking_id'       => $validated['booking_id'],
            'amount'           => $validated['amount'],
            'date_paid'        => $validated['date_paid'],
            'method'           => $validated['method'],
            'reference_number' => $request->reference_number, // Make sure this is passed!
            'notes'            => $request->notes,
            'coordinator_id'   => auth()->id(),
        ]);

        // 3. Return success
        return redirect()->back()->with('success', 'Payment recorded successfully!');
    }
    // ========================================
    // PAYMENT HELPER METHODS (PRIVATE)
    // ========================================
    
    /**
     * Get filtered payments with eager loading
     */
    private function getFilteredPayments($coordinatorId, Request $request)
    {
        return Payment::with(['booking.client', 'booking.event'])
            ->byCoordinator($coordinatorId)
            ->search($request->search)
            ->byMethod($request->method)
            ->byDateRange($request->date_from, $request->date_to)
            ->latest('date_paid')
            ->paginate(15)
            ->appends($request->query());
    }

    /**
     * Calculate remaining balance for a booking
     */
    private function calculateRemainingBalance($booking)
    {
        $totalPaid = $booking->payments->sum('amount');
        $totalAmount = $booking->total_amount ?? $booking->total_price ?? 0;
        return max(0, $totalAmount - $totalPaid);
    }

    /**
     * Calculate payment statistics
     */
    private function calculatePaymentStats($coordinatorId, Request $request)
    {
        $query = Payment::where('coordinator_id', $coordinatorId);

        // Apply same filters as main query
        if ($request->search) {
            $query->search($request->search);
        }
        if ($request->method) {
            $query->byMethod($request->method);
        }
        if ($request->date_from || $request->date_to) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        $totalCollected = $query->sum('amount');
        $totalPayments = $query->count();
        $averagePayment = $totalPayments > 0 ? $totalCollected / $totalPayments : 0;

        // This month's income
        $thisMonth = Payment::where('coordinator_id', $coordinatorId)
            ->whereMonth('date_paid', now()->month)
            ->whereYear('date_paid', now()->year)
            ->sum('amount');

        return [
            'totalCollected' => $totalCollected,
            'totalPayments' => $totalPayments,
            'averagePayment' => $averagePayment,
            'thisMonth' => $thisMonth,
        ];
    }


    // ========================================
    // PROFILE
    // ========================================
    
    public function profile()
    {
        $user = Auth::user();
        return view('coordinator.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|file|max:2048',
            'profile_photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
            'services' => 'nullable|array',
            'event_types' => 'nullable|array',
            'rate' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'bio' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone ?? $user->phone;
        $user->location = $request->location ?? $user->location;
        $user->title = $request->title ?? $user->title;
        $user->bio = $request->bio ?? $user->bio;
        $user->rate = $request->rate ?? $user->rate;
        
        $user->services = $request->services 
            ? json_encode($request->services) 
            : json_encode([]);
            
        if (Schema::hasColumn('users', 'event_types')) {
            $user->event_types = $request->event_types ?? [];
        }

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Avatar upload
        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars/coordinators', 'public');
        }

        // Profile photo upload
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $coordinator = $user->coordinator ?? Coordinator::create([
                'user_id' => $user->id,
                'coordinator_name' => $user->name,
                'expertise' => '',
                'phone_number' => '',
                'address' => '',
                'status' => 'approved'
            ]);

            if ($coordinator->profile_photo && Storage::disk('public')->exists($coordinator->profile_photo)) {
                Storage::disk('public')->delete($coordinator->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/profile_photos', $filename);
            $coordinator->profile_photo = 'profile_photos/' . $filename;
            $coordinator->save();
        }

        $user->save();
        
        return redirect()->route('coordinator.profile')
            ->with('success', 'Profile updated successfully!');
    }

    // ========================================
    // PUBLIC COORDINATOR VIEWS
    // ========================================
    
    /**
     * Show all coordinators grouped by event type (for clients/guests)
     */
    public function index()
    {
        $allCoordinators = Coordinator::with(['user', 'events'])
            ->whereHas('user', function($query) {
                $query->where('is_active', 1);
            })
            ->get();

        $coordinators = $allCoordinators->groupBy(function($coordinator) {
            $firstEvent = $coordinator->events->first();
            return $firstEvent ? $firstEvent->event_type : 'others';
        });

        return view('coordinators.index', compact('coordinators'));
    }

    /**
     * Show single coordinator profile
     */
    public function show($id) 
    {
        $eventType = request('event'); 

        $coordinator = Coordinator::with(['user', 'events', 'bookings'])
            ->findOrFail($id);

        if (!$coordinator->user || !$coordinator->user->is_active) {
            abort(403, 'This coordinator is not available.');
        }

        return view('coordinators.show', compact('coordinator', 'eventType'));
    }

    /**
     * Update Coordinator (Admin function)
     */
    public function update(Request $request, $id)
    {
        // Security Check: Only Admins can do this
        if (strtolower(trim(Auth::user()->role)) !== 'admin') {
            abort(403, 'Unauthorized. Only admins can update coordinators here.');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($user->coordinator) {
            $user->coordinator->update([
                'status' => $request->status ?? $user->coordinator->status,
            ]);
        }

        return redirect()->back()->with('success', 'Coordinator updated successfully.');
    }

    // ========================================
    // REPORTS
    // ========================================
    
    /**
     * BOOKINGS REPORT
     * Shows all bookings with search and date filters
     */
    public function reportBookings(Request $request)
    {
        $coordinator = Auth::user()->coordinator;
        
        $query = Booking::where('coordinator_id', $coordinator->id)
            ->with(['client', 'event', 'coordinator']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('event', function($eventQuery) use ($search) {
                    $eventQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('event_name', 'like', "%{$search}%");
                })
                ->orWhere('event_name', 'like', "%{$search}%")
                ->orWhereHas('coordinator', function($coordQuery) use ($search) {
                    $coordQuery->where('coordinator_name', 'like', "%{$search}%");
                });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('event_date', 'desc')->paginate(15);

        return view('coordinator.reports.bookings', compact('bookings'));
    }

    /**
     * CLIENTS REPORT
     * Shows all clients with their bookings
     */
    public function reportClients(Request $request)
    {
        $coordinator = Auth::user()->coordinator;
        
        $query = Booking::where('coordinator_id', $coordinator->id)
            ->with(['client', 'event', 'coordinator']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('event', function($eventQuery) use ($search) {
                    $eventQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('event_name', 'like', "%{$search}%");
                })
                ->orWhere('event_name', 'like', "%{$search}%")
                ->orWhereHas('coordinator', function($coordQuery) use ($search) {
                    $coordQuery->where('coordinator_name', 'like', "%{$search}%");
                });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('event_date', 'desc')->paginate(15);

        return view('coordinator.reports.clients', compact('bookings'));
    }

    /**
     * FEEDBACK/RATINGS REPORT
     * Shows all ratings and feedback from clients
     */
    public function reportFeedback(Request $request)
    {
        $coordinator = Auth::user()->coordinator;
        
        $query = Booking::where('coordinator_id', $coordinator->id)
            ->whereHas('reviews') // Only bookings with reviews
            ->with(['client', 'event', 'coordinator', 'reviews']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('client', function($clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('event', function($eventQuery) use ($search) {
                    $eventQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('event_name', 'like', "%{$search}%");
                })
                ->orWhere('event_name', 'like', "%{$search}%")
                ->orWhereHas('coordinator', function($coordQuery) use ($search) {
                    $coordQuery->where('coordinator_name', 'like', "%{$search}%");
                })
                ->orWhereHas('reviews', function($reviewQuery) use ($search) {
                    $reviewQuery->where('feedback', 'like', "%{$search}%");
                });
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $ratings = $query->orderBy('event_date', 'desc')->paginate(15);

        return view('coordinator.reports.feedback', compact('ratings'));
    }

    /**
     * INCOME REPORT
     * Shows all payments received with filters
     */
   public function reportIncome(Request $request)
{
    $coordinator = Auth::user()->coordinator;

    $query = Payment::whereHas('booking', function ($q) use ($coordinator) {
        $q->where('coordinator_id', $coordinator->id);
    })
    ->with(['booking.client', 'booking.event']);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->whereHas('booking.client', function ($clientQuery) use ($search) {
                $clientQuery->where('name', 'like', "%{$search}%");
            })

            ->orWhereHas('booking.event', function ($eventQuery) use ($search) {
                $eventQuery->where('name', 'like', "%{$search}%");
            })

            ->orWhereHas('booking', function ($bookingQuery) use ($search) {
                $bookingQuery->where('event_name', 'like', "%{$search}%");
            })

            ->orWhere('reference_number', 'like', "%{$search}%");
        });
    }

    // Date filters
    if ($request->filled('date_from')) {
        $query->whereDate('date_paid', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('date_paid', '<=', $request->date_to);
    }

    // Method filter
    if ($request->filled('method')) {
        $query->where('method', $request->method);
    }

    $payments = $query
        ->orderBy('date_paid', 'desc')
        ->paginate(15);

    return view('coordinator.reports.income', compact('payments'));
}


    /**
     * STORE PAYMENT
     * When coordinator confirms payment from client
     */
// ================= PAYMENTS ==================
public function storePayment(Request $request)
{
    // 1. Validate
    $validated = $request->validate([
        'booking_id'       => 'required|exists:bookings,id',
        'amount'           => 'required|numeric|min:0.01',
        'date_paid'        => 'required|date',
        'method'           => 'required|string',
        'reference_number' => 'nullable|string|max:255', 
        'notes'            => 'nullable|string',
    ]);

    // 2. Get coordinator ID from authenticated user
    $coordinatorId = $this->requireCoordinatorId();
    
    // 3. Verify the booking belongs to this coordinator
    $booking = Booking::where('id', $request->booking_id)
        ->where('coordinator_id', $coordinatorId)
        ->firstOrFail();

    // 4. Create payment with coordinator_id
    Payment::create([
        'booking_id'       => $request->booking_id,
        'coordinator_id'   => $coordinatorId, // ✅ THIS WAS MISSING - Critical for showing in ledger
        'amount'           => $request->amount,
        'date_paid'        => $request->date_paid,
        'method'           => $request->method,
        'reference_number' => $request->reference_number,
        'notes'            => $request->notes,
        'status'           => 'completed',
    ]);

    return back()->with('success', 'Payment recorded successfully!');
}
}