<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookings; // Add this at the top
use App\Models\CoordinatorsInfo; // Make sure to import the model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBookingNotification;



class CoordinatorController extends Controller
{
    public function index()
    {
        return view('coordinators.index');
    }

    public function byEvent($event)
    {
        return view('coordinators.list', compact('event'));
    }

    public function show($event, $id)
    {
        return view('coordinators.show', compact('event', 'id'));
    }

    public function showForClient(CoordinatorsInfo $coordinator)
{
    $averageRating = $coordinator->reviews()->avg('rating') ?? 0;
    return view('client.coordinator-view', compact('coordinator', 'averageRating'));
}


    public function store(Request $request, $coordinatorId)
    {
        $client = Auth::user(); // logged-in client
        $coordinator = CoordinatorsInfo::findOrFail($coordinatorId);

        // Create booking record
        $booking = Bookings::create([
            'name' => $client->name,               // client name
            'event' => $coordinator->business_name, // event/coordinator info
            'status' => 'pending',                 // keep your current column
        ]);

        // Send notification to the coordinator
        Notification::send($coordinator->user, new NewBookingNotification($booking));

        return redirect()->route('client.bookings.index')
            ->with('success', 'Booking request sent! Await coordinator approval.');
    }


public function bookings(Request $request)
{
    // Start query with relationships
    $query = Bookings::with(['client', 'event']); 

    // If search term exists, filter by client name or event name
    if ($request->has('search') && $request->search != '') {
        $search = $request->search;

        $query->whereHas('client', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })->orWhereHas('event', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        });
    }

    // Paginate results
    $bookings = $query->latest()->paginate(10);

    // Pass to Blade
    return view('coordinator.bookings', compact('bookings'));
}
    public function myBookings()
{
    $client = Auth::user();
    $bookings = Bookings::where('name', $client->name)->get(); // Or filter by user if you have user_id
    return view('client.mybookings', compact('bookings'));
}

}
    