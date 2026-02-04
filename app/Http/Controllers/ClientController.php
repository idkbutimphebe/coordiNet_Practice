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
    public function showCoordinator($id)
    {
        $coordinator = Coordinator::with(['user', 'events'])->findOrFail($id);
        $events = $coordinator->events;

        return view('client.coordinators', compact('coordinator', 'events'));
    }

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