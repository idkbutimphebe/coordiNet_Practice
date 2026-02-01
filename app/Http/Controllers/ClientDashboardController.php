<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- ADD THIS
use App\Models\Bookings;             // <-- ADD THIS
use App\Models\CoordinatorsInfo;    // <-- ADD THIS


class ClientDashboardController extends Controller
{

    public function dashboard()
    {
        $coordinators = CoordinatorsInfo::latest()->take(4)->get();
        return view('client.dashboard', compact('coordinators'));
    }

    // Show all coordinators for client
public function coordinatorsPage()
{
    $coordinators = CoordinatorsInfo::withCount('reviews')->get();

    // 🚨 DEBUG — DO NOT REMOVE UNTIL CONFIRMED
    foreach ($coordinators as $coord) {
        if (is_array($coord)) {
            dd('ARRAY FOUND — controller is converting data');
        }
    }

    return view('client.coordinators', compact('coordinators'));
}

public function bookCoordinator($coordinatorId)
{
    $client = Auth::user(); // Get the logged-in client
    $coordinator = CoordinatorsInfo::findOrFail($coordinatorId);

    // Create the booking record
     $booking = Bookings::create([
        'name' => $client->name,         // Client's name
        'event' => 'New Booking Event',  // You can replace with form input
        'status' => 'Pending',           // Default status
    ]);

    // Optional: Redirect back with success message
    return redirect()->route('client.bookings.index')
                 ->with('success', 'Booking recorded successfully!');
}


public function myBookings()
{
    $clientId = Auth::id();

    $bookings = Bookings::with([
            'services',
            'coordinator',
            'eventInfo'
        ])
        ->where('client_id', $clientId)
        ->latest()
        ->get();

    return view('client.booking.index', compact('bookings'));
}



    public function index()
{
    $bookings = Bookings::with('services')->latest()->get();

    return view('client.booking.index', compact('bookings'));
}

}
