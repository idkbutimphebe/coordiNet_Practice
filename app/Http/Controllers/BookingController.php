<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // Display all bookings with search and pagination
    public function index(Request $request)
    {
        $query = Booking::with(['event.coordinator']); // eager load relationships

        if ($request->filled('search')) {
            $query->whereHas('event', function($q) use ($request) {
                // NOTE: Ensure your 'events' table has a 'name' column. 
                // If it is 'event_name', change 'name' to 'event_name' below.
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhereHas('coordinator', fn($c) => $c->where('name', 'like', "%{$request->search}%"));
            });
        }

        // ✅ FIXED: Changed 'booking_date' to 'event_date'
        // (You can also use 'created_at' if you want to sort by when the booking was made)
        $bookings = $query->orderBy('event_date', 'desc')
                          ->paginate(10); 

        return view('bookings.index', compact('bookings'));
    }

    // Show a single booking
    public function show($id)
    {
        $booking = Booking::with(['event.coordinator', 'client', 'services'])->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }
}