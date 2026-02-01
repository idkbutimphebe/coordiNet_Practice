<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Coordinator; // Added this to access Coordinator model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CoordinatorController extends Controller
{
    // ================= ADDED METHODS TO FIX ERRORS =================
    
    /**
     * Display a listing of the coordinators.
     * This fixes the "Undefined method index" error.
     */
    public function index()
    {
        $coordinators = Coordinator::with('user')->paginate(10);
        return view('coordinators.index', compact('coordinators'));
    }

    /**
     * Handle the coordinators list specifically if named differently in routes.
     */
    public function coordinators()
    {
        return $this->index();
    }

    // ================= DASHBOARD =================
    public function dashboard()
    {
        $pendingBookings = Booking::where('status', 'pending')->get();
        $completedBookings = Booking::where('status', 'completed')->count();
        $upcomingEvents = Booking::whereDate('booking_date', '>=', now())->count();

        $stats = [
            [
                'label' => 'Completed Bookings',
                'value' => $completedBookings,
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>',
                'link'  => route('coordinator.bookings', ['status' => 'completed'])
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
            'completed' => $completedBookings,
            'pending'   => $pendingBookings->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        $activityLabels = [];
        $activityData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $activityLabels[] = $date->format('D');
            $activityData[]   = Booking::whereDate('booking_date', $date)->count();
        }

        return view('coordinator.dashboard', compact(
            'pendingBookings', 'stats', 'statusChart', 'activityLabels', 'activityData'
        ));
    }

    // ================= BOOKINGS LIST =================
    public function bookings(Request $request)
    {
        $query = Booking::with('client.user');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search) {
            $query->whereHas('client.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);
        return view('coordinator.bookings', compact('bookings'));
    }

    // ================= SINGLE BOOKING =================
    public function bookingsShow($id)
    {
        $booking = Booking::with('client.user')->findOrFail($id);
        return view('coordinator.bookings-show', compact('booking'));
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

        // 1. Fetch Client Bookings
        $bookings = Booking::with('client.user')
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($bookings as $booking) {
            $events[] = [
                'id' => 'booking-' . $booking->id,
                'title' => 'Client: ' . ($booking->client->user->name ?? 'Booking'),
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

        // 2. Fetch Personal Schedules
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

    // ================= SAVE EVENT (FIXED) =================
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

    // ================= PROFILE (SHOW) =================
    public function profile()
    {
        $user = Auth::user();
        return view('coordinator.profile', compact('user'));
    }

    // ================= PROFILE (UPDATE) =================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'portfolio.*.image' => 'nullable|image|max:2048', 
            'rate' => 'nullable|numeric',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $currentPortfolio = is_string($user->portfolio) ? json_decode($user->portfolio, true) : ($user->portfolio ?? []);
        
        if ($request->has('portfolio')) {
            foreach ($request->portfolio as $key => $item) {
                if (isset($item['image']) && $item['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $path = $item['image']->store('portfolio', 'public');
                    $currentPortfolio[$key]['image'] = $path;
                }
                if (isset($item['desc'])) {
                    $currentPortfolio[$key]['desc'] = $item['desc'];
                }
            }
        }

        $user->name = $request->name;
        $user->title = $request->title;
        $user->phone = $request->phone;
        $user->location = $request->location;
        $user->bio = $request->bio;
        $user->rate = $request->rate;
        $user->is_active = $request->is_active; 
        
        $user->services = $request->services ?? [];
        $user->portfolio = $currentPortfolio;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}