<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Show client's bookings
     */
    public function bookings()
    {
        $bookings = Booking::with(['event', 'event.coordinator'])
            ->where('client_id', Auth::id())
            ->orderBy('booking_date', 'desc')
            ->get();

        return view('client.bookings', compact('bookings'));
    }

    /**
     * Show single booking
     */
    public function showBooking(Booking $booking)
    {
        return view('client.booking-show', compact('booking'));
    }
}
