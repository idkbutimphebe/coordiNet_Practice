<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Reviews;
use App\Models\User; // <-- Needed for coordinators
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    // ================= DASHBOARD ==================
    public function dashboard()
    {
        $user = Auth::user();

        // Latest 5 bookings for this client
        $bookings = Booking::with('coordinator')
            ->where('client_id', $user->id)
            ->orderBy('event_date', 'desc') // ✅ FIXED
            ->take(5)
            ->get();



            // Calculate stats
        $totalBookings = $bookings->count();
        $upcomingEvents = $bookings->where('status', 'pending')->count();
        $completedEvents = $bookings->where('status', 'completed')->count();
             // Dashboard stats
    $stats = [
        [
            'label' => 'My Bookings',
            'value' => $totalBookings,
            'link'  => route('client.bookings.index'),
        ],
        [
            'label' => 'Upcoming Events',
            'value' => $upcomingEvents,
            'link'  => route('client.bookings.index', ['status' => 'pending']),
        ],
        [
            'label' => 'Completed Events',
            'value' => $completedEvents,
            'link'  => route('client.bookings.index', ['status' => 'completed']),
        ],
    ];

        // Top 4 coordinators: order by `rating` or `rate` if available, otherwise by newest
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
        $bookings = Booking::with('coordinator')
            ->where('client_id', $user->id)
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();

        // $bookings = Booking::with(['coordinator'])
        //     ->where('client_id', Auth::id())
        //     ->orderBy('event_date', 'desc') // ✅
        //     ->paginate(10);


        return view('client.booking.index', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        if ($booking->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        return view('client.booking.show', compact('booking'));
    }

public function storeBooking(Request $request)
{
    $request->validate([
        'coordinator_id' => 'required|exists:users,id',
        'event_id'       => 'required|exists:events,id', // required now
        'event_name'     => 'nullable|string|max:255',
        'event_date'     => 'required|date', // matches your migration
        'start_time'     => 'required',
        'end_time'       => 'required',
        'note'           => 'nullable|string|max:1000',
    ]);

    Booking::create([
        'client_id'      => Auth::id(),
        'coordinator_id' => $request->coordinator_id,
        'event_id'       => $request->event_id,
        'event_name'     => $request->event_name,
        'event_date'     => $request->event_date,
        'start_time'     => $request->start_time,
        'end_time'       => $request->end_time,
        'note'           => $request->note,
        'status'         => 'pending',
        'total_amount'   => 0,
    ]);

    return redirect()->route('client.bookings.index')
                     ->with('success', 'Booking created! Waiting for coordinator confirmation.');
}


    public function updateBooking(Request $request, $id)
    {
        $booking = Booking::where('coordinator_id', Auth::id())->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->status = $request->status;
        $booking->save();

        return back()->with('success', "Booking {$request->status} successfully!");
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
    public function updateProfile(Request $request)
{
    $user = auth()->user();

    // Get the client record, or create it if it doesn't exist
    $client = $user->client;
    if (!$client) {
        $client = new \App\Models\Client();
        $client->user_id = $user->id;
        $client->phone_number = ''; // default empty
        $client->address = '';      // default empty
        $client->save();
    }


    // Validate inputs
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password' => 'nullable|confirmed|min:6',
    ]);

    // Update user
    $user->update([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $data['password'] ? bcrypt($data['password']) : $user->password,
    ]);

    // Update avatar
    if ($request->hasFile('avatar')) {
        // Delete old avatar if it exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $avatarPath = $request->file('avatar')->store('avatars/clients', 'public');
        $user->avatar = $avatarPath;
        $user->save();
    }

    // Update client details
    $client->phone_number = $data['phone_number'] ?? '';
    $client->address = $data['address'] ?? '';
    $client->save();

    return redirect()->route('client.profile')->with('success', 'Profile updated successfully!');
}


    public function edit()
{
    $user = auth()->user();
    $client = $user->client; // Get the related client record

    return view('client.profile', compact('user', 'client'));
}
//select coordinator and show events
public function showCoordinator($id)
{
    $coordinator = Coordinator::findOrFail($id);

    $events = $coordinator->events; // 👈 FROM events table

    return view('client.coordinators', compact('coordinator', 'events'));
}

public function coordinators()
{
    $coordinators = User::where('role', 'coordinator')
        ->where('is_active', 1)
        ->with('events') // IMPORTANT for event selection
        ->orderBy('rate', 'asc')
        ->get();

    return view('client.coordinators', compact('coordinators'));
}

}
