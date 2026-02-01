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
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhereHas('coordinator', fn($c) => $c->where('name', 'like', "%{$request->search}%"));
            });
        }

        $bookings = $query->orderBy('booking_date', 'desc')
                          ->paginate(10); // pagination

        return view('bookings.index', compact('bookings'));
    }

    // Show a single booking
    public function show($id)
    {
        $booking = Booking::with(['event.coordinator', 'client', 'services'])->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }
}
