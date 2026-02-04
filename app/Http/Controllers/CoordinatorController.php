<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Event;
use App\Models\Coordinator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CoordinatorController extends Controller
{
    private function coordinatorIdOrNull(): ?int
    {
        $coordinator = Auth::user()?->coordinator;
        if (!$coordinator) {
            return null;
        }
        return (int) $coordinator->id;
    }
    
    private function requireCoordinatorId(): int
    {
        $id = $this->coordinatorIdOrNull();
        if (!$id) {
            abort(403, 'Coordinator profile not found.');
        }
        return $id;
    }
    // ================= ADDED METHODS TO FIX ERRORS =================
    
    public function index()
    {
        $coordinators = Coordinator::with('user')->paginate(10);
        return view('coordinators.index', compact('coordinators'));
    }

    public function coordinators()
    {
        return $this->index();
    }

    // ================= DASHBOARD =================
    public function dashboard()
    {
        $coordinatorId = $this->coordinatorIdOrNull();
        if (!$coordinatorId) {
            // Coordinator user exists but no coordinators table row.
            // Show empty dashboard instead of hard 403.
            $pendingBookings = collect();
            $confirmedBookings = 0;
            $upcomingEvents = 0;
            $stats = [
                [
                    'label' => 'Confirmed Bookings',
                    'value' => 0,
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>',
                    'link'  => route('coordinator.bookings', ['status' => 'confirmed'])
                ],
                [
                    'label' => 'Pending Bookings',
                    'value' => 0,
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                    'link'  => route('coordinator.bookings', ['status' => 'pending'])
                ],
                [
                    'label' => 'Upcoming Events',
                    'value' => 0,
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                    'link'  => route('coordinator.schedule')
                ],
            ];

            $statusChart = [
                'completed' => 0,
                'pending'   => 0,
                'cancelled' => 0,
            ];

            $activityLabels = [];
            $activityData   = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $activityLabels[] = $date->format('D');
                $activityData[] = 0;
            }

            return view('coordinator.dashboard', compact(
                'pendingBookings', 'stats', 'statusChart', 'activityLabels', 'activityData'
            ))->with('error', 'Coordinator profile not found. Please complete coordinator registration or contact admin.');
        }

        $pendingBookings = Booking::where('coordinator_id', $coordinatorId)
            ->where('status', 'pending')
            ->with('client')
            ->get();

        $confirmedBookings = Booking::where('coordinator_id', $coordinatorId)
            ->where('status', 'confirmed')->count();

        $upcomingEvents = Booking::where('coordinator_id', $coordinatorId)
            ->whereDate('event_date', '>=', now())->count();

        $stats = [
            [
                'label' => 'Confirmed Bookings',
                'value' => $confirmedBookings,
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>',
                'link'  => route('coordinator.bookings', ['status' => 'confirmed'])
            ],
            [
                'label' => 'Pending Bookings',
                'value' => $pendingBookings->count(),
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'link'  => route('coordinator.bookings', ['status' => 'pending'])
            ],
            [
                'label' => 'Upcoming Events',
                'value' => $upcomingEvents,
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                'link'  => route('coordinator.schedule')
            ],
        ];

        $statusChart = [
            // Dashboard view expects these keys
            'completed' => $confirmedBookings,
            'pending'   => $pendingBookings->count(),
            'cancelled' => Booking::where('coordinator_id', $coordinatorId)
                ->where('status', 'cancelled')->count(),
        ];

        $activityLabels = [];
        $activityData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $activityLabels[] = $date->format('D');
            $activityData[] = Booking::whereDate('event_date', $date)
                         ->where('coordinator_id', $coordinatorId)
                         ->count();

        }

        return view('coordinator.dashboard', compact(
            'pendingBookings', 'stats', 'statusChart', 'activityLabels', 'activityData'
        ));
    }

    // ================= BOOKINGS LIST =================
    public function bookings(Request $request)
    {
        $coordinatorId = $this->coordinatorIdOrNull();
        if (!$coordinatorId) {
            return redirect()->route('coordinator.profile')
                ->with('error', 'Coordinator profile not found. Please complete your profile first.');
        }
        $query = Booking::where('coordinator_id', $coordinatorId)
            ->with(['client', 'event']);

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('client', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhere('event_name', 'like', "%{$search}%");
            });
        }

        $orderBy = $request->get('orderBy', 'event_date');
        $bookings = $query->orderBy($orderBy, 'desc')->paginate(10);
        return view('coordinator.bookings', compact('bookings'));
    }

    // ================= SINGLE BOOKING =================
    public function bookingsShow($id)
    {
        $coordinatorId = $this->requireCoordinatorId();
        $booking = Booking::with(['client', 'event', 'coordinator.user'])
            ->where('coordinator_id', $coordinatorId)
            ->findOrFail($id);

        return view('coordinator.bookings-show', compact('booking'));
    }

    // ================= UPDATE BOOKING STATUS =================
    public function updateBooking(Request $request, $id)
    {
        $coordinatorId = $this->requireCoordinatorId();
        $booking = Booking::where('coordinator_id', $coordinatorId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
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

    // ================= SCHEDULE PAGE =================
    public function schedule()
    {
        return view('coordinator.schedule');
    }

    // ================= FETCH EVENTS (JSON) =================
    public function getScheduleEvents()
    {
        $events = [];
        $coordinatorId = Auth::id();

        $bookings = Booking::with('client')
            ->where('coordinator_id', $coordinatorId)
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($bookings as $booking) {
            $events[] = [
                'id' => 'booking-' . $booking->id,
                'title' => 'Client: ' . ($booking->client->name ?? 'Booking'),
                'start' => $booking->booking_date,
                'backgroundColor' => '#3E3F29',
                'borderColor' => '#3E3F29',
                'extendedProps' => [
                    'location' => $booking->location ?? 'TBD',
                    'status'   => ucfirst($booking->status),
                    'type'     => 'Client Booking'
                ]
            ];
        }

        try {
            $schedules = Schedule::where('coordinator_id', $coordinatorId)->get();

            foreach ($schedules as $schedule) {
                $events[] = [
                    'id' => 'schedule-' . $schedule->id,
                    'title' => $schedule->name,
                    'start' => $schedule->date . 'T' . $schedule->start_time,
                    'end'   => $schedule->date . 'T' . $schedule->end_time,
                    'backgroundColor' => '#A1BC98',
                    'borderColor' => '#A1BC98',
                    'extendedProps' => [
                        'location' => $schedule->location ?? '', 
                        'type'     => 'Personal Schedule'
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Schedule table error: ' . $e->getMessage());
        }

        return response()->json($events);
    }

    // ================= SAVE EVENT =================
    public function saveEvent(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255', 
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
            'location'   => 'nullable|string|max:255', 
        ]);

        try {
            $formattedStart = Carbon::parse($request->start_time)->format('H:i:s');
            $formattedEnd   = Carbon::parse($request->end_time)->format('H:i:s');

            $event = Schedule::create([
                'coordinator_id' => Auth::id(),
                'name'           => $request->name,
                'date'           => $request->date,
                'start_time'     => $formattedStart,
                'end_time'       => $formattedEnd,
                'location'       => $request->location,
            ]);

            return response()->json([
                'success' => true,
                'event'   => $event
            ]);

        } catch (\Exception $e) {
            Log::error('Save Event Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Database Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ================= REVIEWS / RATINGS =================
    public function reviews()
    {
        $coordinatorId = Auth::id();
        $reviews = Reviews::where('coordinator_id', $coordinatorId)
            ->with('client')
            ->latest()
            ->get();

        $averageRating = $reviews->avg('rating');
        $formattedAvg = number_format((float)$averageRating, 1);
        $totalReviews = $reviews->count();

        return view('coordinator.ratings', compact('reviews', 'formattedAvg', 'totalReviews'));
    }

    // ================= PROFILE =================
    public function profile()
    {
        $user = Auth::user();
        return view('coordinator.profile', compact('user'));
    }

public function updateProfile(Request $request)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email,' . $user->id,
        'avatar'   => 'nullable|file|max:2048',
        'password' => 'nullable|string|min:6|confirmed',
        'services' => 'nullable|array', 
        'event_types' => 'nullable|array',
        'rate'     => 'nullable|numeric|min:0',
        'is_active'=> 'nullable|boolean',
        'bio'      => 'nullable|string|max:1000',
        'location' => 'nullable|string|max:255',
        'title'    => 'nullable|string|max:255',
        'event_type_id' => 'nullable|exists:event_types,id',
    ]);

    // Update User Fields
    $user->name      = $request->name;
    $user->email     = $request->email;
    $user->phone     = $request->phone ?? $user->phone;
    $user->location  = $request->location ?? $user->location;
    $user->title     = $request->title ?? $user->title;
    $user->bio       = $request->bio ?? $user->bio;
    $user->rate      = $request->rate ?? $user->rate;
    $user->is_active = $request->has('is_active') ? 1 : 0;
    $user->services  = $request->services ? json_encode($request->services) : json_encode([]);
    // Persist event types only if the DB column exists (migration ran).
    if (Schema::hasColumn('users', 'event_types')) {
        $user->event_types = $request->event_types ?? [];
    }

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->avatar = $request->file('avatar')->store('avatars/coordinators', 'public');
    }

    // Save the User first
    try {
        $user->save();
    } catch (\Illuminate\Database\QueryException $e) {
        // If migration wasn't run yet, avoid a 500 and show a helpful message.
        if (!Schema::hasColumn('users', 'event_types')) {
            return back()
                ->withInput()
                ->withErrors(['event_types' => 'Database not updated yet. Please run: php artisan migrate']);
        }

        throw $e;
    }

    // REMOVED the extra $user->save() calls here to prevent overwrite conflicts

    return redirect()->route('coordinator.profile')->with('success', 'Profile updated successfully!');
}
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'coordinator') {
                abort(403, 'Unauthorized.');
            }
            if (!Auth::user()->is_active) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is pending admin approval.');
            }

            // Ensure there is a coordinators table row for this coordinator user.
            // This prevents 403s and allows bookings/events FK constraints to work.
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if (!$user->coordinator) {
                Coordinator::create([
                    'user_id' => $user->id,
                    'coordinator_name' => $user->name,
                    'expertise' => '',
                    'phone_number' => '',
                    'address' => '',
                    'status' => 'approved',
                ]);
            }

            return $next($request);
        });
    }

//  $user->save();

//     return redirect()->route('coordinator.profile')->with('success', 'Profile updated successfully!');
// }
}
