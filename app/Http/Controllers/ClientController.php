<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Reviews;
use App\Models\User; 
use App\Models\Event;
use App\Models\Coordinator;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Needed for date comparisons

class ClientController extends Controller
{
    // ================= DASHBOARD ==================
// ================= DASHBOARD ==================
public function dashboard()
{
    $user = Auth::user();

    // Latest 5 bookings for this client, eager loading the coordinator
    $bookings = Booking::with('coordinator') // <- correct relationship
        ->where('client_id', $user->id)
        ->orderBy('event_date', 'desc')
        ->take(5)
        ->get();

    // --- NEW DATE-BASED LOGIC ---
    $totalBookings = Booking::where('client_id', $user->id)->count();

    $upcomingEvents = Booking::where('client_id', $user->id)
        ->whereDate('event_date', '>=', Carbon::today())
        ->where('status', '!=', 'cancelled')
        ->count();

    $completedEvents = Booking::where('client_id', $user->id)
        ->whereDate('event_date', '<', Carbon::today())
        ->where('status', '!=', 'cancelled')
        ->count();

    // Dashboard stats structure
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

    // Top 4 coordinators logic
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
        
        $booking->load(['event', 'coordinator.user']);

        // Prevent "Table not found" error by setting empty services list
        $booking->setRelation('services', collect());

        return view('client.booking.show', compact('booking'));
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'coordinator_id' => 'required|exists:users,id',
            'event_type'     => 'nullable|string|max:255',
            'event_date'     => 'required|date',
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

    // ================= RATINGS ==================
    public function ratings()
    {
        $clientId = Auth::id();

        // Fixed relationship access
        $reviews = Reviews::with('coordinator')
            ->where('client_id', $clientId)
            ->orderByDesc('created_at')
            ->get();

        return view('client.ratings', compact('reviews'));
    }

    // ================= PROFILE ==================
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $updateData = [
            'name' => $data['name'],
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

    public function edit()
    {
        $user = auth()->user();
        $client = $user->client;

        return view('client.profile', compact('user', 'client'));
    }

    // ================= COORDINATORS ==================
public function viewCoordinator($id)
    {
        // 1. Get the User (Coordinator)
        $coordinatorUser = User::where('role', 'coordinator')
            ->where('id', $id)
            ->where('is_active', 1)
            ->with('coordinator') // Load the profile relationship
            ->firstOrFail();

        // 2. Get the Coordinator Profile Model
        $coordinator = $coordinatorUser->coordinator;

        // Safety check: Ensure profile exists
        if (!$coordinator) {
            return back()->with('error', 'This coordinator has not set up their profile yet.');
        }

        // 3. Prepare Services Array (Decode JSON)
        $servicesRaw = $coordinator->services ?? [];
        $servicesArray = is_string($servicesRaw) 
            ? json_decode($servicesRaw, true) 
            : ($servicesRaw ?? []);
            
        // Ensure it's an array to prevent errors
        if (!is_array($servicesArray)) {
            $servicesArray = [];
        }

        // 4. Calculate Ratings
        $averageRating = Reviews::where('coordinator_id', $coordinator->id)->avg('rating') ?? 0;
        $averageRating = number_format($averageRating, 1);
        $totalReviews = Reviews::where('coordinator_id', $coordinator->id)->count();

        // 5. Get Reviews List
        $reviews = Reviews::where('coordinator_id', $coordinator->id)
            ->with('client.user')
            ->latest()
            ->get();

        // 6. Check if current client has a completed booking (for showing rating stars)
        $hasCompletedBooking = false;
        if (Auth::check()) {
            $hasCompletedBooking = Booking::where('client_id', Auth::id())
                ->where('coordinator_id', $coordinator->id)
                ->where(function($query) {
                    $query->where('status', 'completed')
                          ->orWhereDate('event_date', '<', now());
                })
                ->exists();
        }

        // 7. Pass ALL variables to the view
        return view('client.coordinator-view', compact(
            'coordinatorUser',      // The User model (name, email, avatar)
            'coordinator',          // The Profile model (bio, rate, portfolio)
            'servicesArray',        // The decoded services list
            'averageRating',        // Formatted rating (e.g., "4.5")
            'totalReviews',         // Count of reviews
            'reviews',              // List of review objects
            'hasCompletedBooking'   // Boolean for review permission
        ));
    }

    // THIS FUNCTION WAS MISSING IN YOUR PREVIOUS CODE
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
}