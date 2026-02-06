<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth; // Required for auth()->id() check

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
        $bookings = $query->orderBy('event_date', 'desc')
                          ->paginate(10); 

        return view('bookings.index', compact('bookings'));
    }

    // Show a single booking
    public function show($id)
    {
        // Added 'client' to relationships since your view uses it
        $booking = Booking::with(['event.coordinator', 'client', 'services'])->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Store a newly created booking in storage.
     * (Useful if you have a Create Booking form)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_type' => 'required|string|max:255',
            'event_date' => 'required|date|after:today',
            'location'   => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
            'note'       => 'nullable|string',
        ]);

        Booking::create([
            'client_id'  => Auth::id(), // Assign to currently logged in user
            'event_type' => $validated['event_type'],
            'event_date' => $validated['event_date'],
            'location'   => $validated['location'],
            'start_time' => $validated['start_time'],
            'end_time'   => $validated['end_time'],
            'notes'      => $validated['note'],
            'status'     => 'pending',
        ]);

        // Adjust redirect route as needed
        return redirect()->back()->with('success', 'Booking created successfully!');
    }

    /**
     * Update the specified booking in storage.
     * ✅ THIS FIXES YOUR "Method does not exist" ERROR
     */
    public function update(Request $request, $id)
    {
        // 1. Find the booking.
        // We check 'client_id' to make sure users can only edit THEIR own bookings.
        $booking = Booking::where('id', $id);

        // If a user is logged in, enforce ownership
        if (Auth::check()) {
            $booking->where('client_id', Auth::id());
        }
            
        $booking = $booking->firstOrFail();

        // 2. Security: Ensure they can only edit pending bookings
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'You can only edit details for pending bookings.');
        }

        // 3. Validate the incoming data from your modal form
        $validated = $request->validate([
            'event_type' => 'required|string|max:255',
            'event_date' => 'required|date|after:today',
            'location'   => 'required|string|max:255',
            'start_time' => 'required',
            'end_time'   => 'required',
            'note'       => 'nullable|string',
        ]);

        // 4. Update the record
        $booking->update([
            'event_type' => $validated['event_type'],
            'event_date' => $validated['event_date'],
            'location'   => $validated['location'],
            'start_time' => $validated['start_time'],
            'end_time'   => $validated['end_time'],
            'notes'      => $validated['note'], // Maps form 'note' to DB 'notes'
        ]);

        // 5. Redirect back with success message
        return redirect()->back()->with('success', 'Booking details updated successfully!');
    }
}