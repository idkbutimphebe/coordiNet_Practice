<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reviews;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a new review/rating
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $booking = Booking::findOrFail($request->booking_id);

        // Check if this client has already rated this booking
        $existingReview = Reviews::where('booking_id', $booking->id)
                                 ->where('client_id', Auth::id())
                                 ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already rated this booking.');
        }

        // Create review
        Reviews::create([
            'booking_id' => $booking->id,
            'client_id' => Auth::id(),
            'coordinator_id' => $booking->coordinator_id,
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]); 

        return redirect()->back()->with('success', 'Thank you for rating your coordinator!');
    }
}
