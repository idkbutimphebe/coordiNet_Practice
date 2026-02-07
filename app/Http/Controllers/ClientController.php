<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Event;
use App\Models\Coordinator;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ClientController extends Controller
{
    // ================= DASHBOARD ==================
    public function dashboard()
    {
        $user = Auth::user();

        // Latest 5 bookings
        $bookings = Booking::with('coordinator')
            ->where('client_id', $user->id)
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();

        // Stats
        $totalBookings = Booking::where('client_id', $user->id)->count();

        $upcomingEvents = Booking::where('client_id', $user->id)
            ->whereDate('event_date', '>=', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->count();

        $completedEvents = Booking::where('client_id', $user->id)
            ->whereDate('event_date', '<', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->count();

        $stats = [
            [
                'label' => 'My Bookings',
                'value' => $totalBookings,
                'link'  => route('client.bookings.index'),
            ],
            [
                'label' => 'Upcoming Events',
                'value' => $upcomingEvents,
                'link'  => route('client.bookings.index'),
            ],
            [
                'label' => 'Completed Events',
                'value' => $completedEvents,
                'link'  => route('client.bookings.index'),
            ],
        ];

        // Top Coordinators
        if (Schema::hasColumn('users', 'rating')) {
            $orderColumn = 'rating';
        } elseif (Schema::hasColumn('users', 'rate')) {
            $orderColumn = 'rate';
        } else {
            $orderColumn = 'created_at';
        }

        $coordinators = User::where('role', 'coordinator')
            ->orderBy($orderColumn, 'desc')
            ->take(4)
            ->get();

        return view('client.dashboard', compact('user', 'bookings', 'stats', 'coordinators'));
    }

    // ================= BOOKINGS ==================
    public function bookings()
    {
        $user = Auth::user();

        $bookings = Booking::with(['event', 'coordinator.user'])
            ->where('client_id', $user->id)
            ->orderBy('event_date', 'desc')
            ->paginate(10);

        return view('client.booking.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        if ($booking->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $booking->load(['event', 'coordinator.user', 'payments']);

        // Prevent relation errors
        $booking->setRelation('services', collect());

        return view('client.booking.show', compact('booking'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'coordinator_id' => 'required|exists:users,id',
            'event_type'     => 'nullable|string|max:255',
            'event_date'     => 'required|date',
            'location'       => 'required|string|max:255',
            'start_time'     => 'required',
            'end_time'       => 'required',
            'note'           => 'nullable|string|max:1000',
        ]);

        $coordinatorUser = User::findOrFail($request->coordinator_id);
        $coordinatorModel = $coordinatorUser->coordinator;

        if (!$coordinatorModel) {
            return back()
                ->withInput()
                ->withErrors(['coordinator_id' => 'Selected coordinator profile is missing. Please contact admin.']);
        }

        $eventName = $request->event_type ?: 'Event';

        $event = Event::create([
            'coordinator_id' => $coordinatorModel->id,
            'event_name'     => $eventName,
            'event_type'     => $request->event_type ?: $eventName,
            'description'    => $request->note ?? '',
        ]);

        Booking::create([
            'client_id'      => Auth::id(),
            'coordinator_id' => $coordinatorModel->id,
            'event_id'       => $event->id,
            'event_name'     => $eventName,
            'event_date'     => $request->event_date,
            'location'       => $request->location,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'note'           => $request->note,
            'status'         => 'pending',
            'total_amount'   => 0,
        ]);

        return redirect()->route('client.bookings.index')
            ->with('success', 'Booking created! Status: pending.');
    }

    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::where('client_id', Auth::id())->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->status = $request->status;
        $booking->save();

        return back()->with('success', "Booking {$request->status} successfully!");
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
            'note'       => 'nullable|string',
        ]);

        $booking->update([
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
            'note'       => $request->note,
            'event_type' => $request->event_type,
        ]);

        return back()->with('success', 'Booking details updated successfully.');
    }

    // ================= RATINGS ==================
    public function ratings()
    {
        $clientId = Auth::id();

        $reviews = Reviews::with('coordinator')
            ->where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->get();

        return view('client.ratings', compact('reviews'));
    }

    // ================= PROFILE ==================
    public function edit()
    {
        $user = auth()->user();
        $client = $user->client;

        return view('client.profile', compact('user', 'client'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $client = $user->client;
        
        if (!$client) {
            $client = new Client();
            $client->user_id = $user->id;
            $client->phone_number = '';
            $client->address = ''; 
            $client->save();
        }

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:500',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password'     => 'nullable|confirmed|min:6',
        ]);

        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        $user->update($updateData);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $avatarPath = $request->file('avatar')->store('avatars/clients', 'public');
            $user->avatar = $avatarPath;
            $user->save();
        }

        $client->phone_number = $data['phone_number'] ?? '';
        $client->address = $data['address'] ?? '';
        $client->save();

        return redirect()->route('client.profile')->with('success', 'Profile updated successfully!');
    }

    // ================= COORDINATORS VIEW & LIST ==================
    
    // List all coordinators
    public function coordinators()
    {
        $query = User::where('role', 'coordinator')->where('is_active', 1);

        if (Schema::hasColumn('users', 'rate')) {
            $query->orderBy('rate', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $coordinators = $query->get();

        return view('client.coordinators', compact('coordinators'));
    }

    // View specific coordinator (FIXED REVIEWS SECTION)
    public function viewCoordinator($id)
    {
        // 1. Get the User (Coordinator)
        $coordinatorUser = User::where('role', 'coordinator')
            ->where('id', $id)
            ->where('is_active', 1)
            ->with('coordinator') 
            ->firstOrFail();

        // 2. Get the Coordinator Profile Model
        $coordinator = $coordinatorUser->coordinator;

        if (!$coordinator) {
            return back()->with('error', 'This coordinator has not set up their profile yet.');
        }

        // 3. Prepare Services Array
        $servicesRaw = $coordinator->services ?? [];
        $servicesArray = is_string($servicesRaw) 
            ? json_decode($servicesRaw, true) 
            : ($servicesRaw ?? []);
            
        if (!is_array($servicesArray)) {
            $servicesArray = [];
        }

        // 4. Calculate Ratings
        $averageRating = Reviews::where('coordinator_id', $coordinator->id)->avg('rating') ?? 0;
        $averageRating = number_format($averageRating, 1);
        $totalReviews = Reviews::where('coordinator_id', $coordinator->id)->count();

        // 5. Get Reviews List (FIXED)
        // Ensure 'client' relation exists in your Reviews model.
        $reviews = Reviews::where('coordinator_id', $coordinator->id)
            ->with('client') 
            ->latest()
            ->get();

        // 6. Check for completed booking (Permissions)
        $hasCompletedBooking = false;
        if (Auth::check()) {
            $hasCompletedBooking = Booking::where('client_id', Auth::id())
                ->where('coordinator_id', $coordinator->id)
                ->where(function($query) {
                    $query->where('status', 'completed')
                          ->orWhereDate('event_date', '<', Carbon::now());
                })
                ->exists();
        }

        return view('client.coordinator-view', compact(
            'coordinatorUser',
            'coordinator',
            'servicesArray',
            'averageRating',
            'totalReviews',
            'reviews',
            'hasCompletedBooking'
        ));
    }

    // ================= REPORTS ==================
    public function bookingPaymentBreakdown(Booking $booking)
    {
        if ($booking->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $booking->load(['coordinator.user', 'payments', 'event']);

        return view('client.reports.payment-breakdown', compact('booking'));
    }

    public function reportPaymentBreakdown(Request $request)
    {
        $userId = Auth::id();

        $query = Booking::where('client_id', $userId)
            ->with(['coordinator.user', 'payments', 'event']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('event_name', 'like', "%$search%")
                  ->orWhereHas('coordinator', function($c) use ($search) {
                        $c->where('coordinator_name', 'like', "%$search%");
                  });
            });
        }

        // Date filters
        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Fetch bookings first to filter by computed payment status if needed
        $bookings = $query->orderBy('event_date', 'desc')->get();

        if ($request->filled('payment_status')) {
            $paymentStatus = $request->payment_status;
            $bookings = $bookings->filter(function ($booking) use ($paymentStatus) {
                // Ensure getPaymentStatusAttribute() exists in Booking model
                return $booking->payment_status === $paymentStatus; 
            })->values();
        }

        // Calculate totals
        $totalAmount = $bookings->sum('total_amount');
        $totalPaid = $bookings->sum('total_paid'); // Ensure getTotalPaidAttribute() exists or use relationship sum
        $totalBalance = $totalAmount - $totalPaid;

        return view('client.reports.payment-breakdown', compact(
            'bookings',
            'totalAmount',
            'totalPaid',
            'totalBalance'
        ));
    }
}