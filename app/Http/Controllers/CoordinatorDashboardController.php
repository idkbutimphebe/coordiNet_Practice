<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class CoordinatorController extends Controller
{
    // ================= DASHBOARD =================
    public function dashboard()
    {
        // Pending bookings
        $pendingBookings = Booking::where('status', 'pending')->get();

        // Completed bookings
        $completedBookings = Booking::where('status', 'completed')->count();

        // Active upcoming events (future bookings)
        $upcomingEvents = Booking::whereDate('booking_date', '>=', now())->count();

        $stats = [
            [
                'label' => 'Completed Bookings',
                'value' => $completedBookings,
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m6-4a4 4 0 11-8 0 4 4 0 018 0z"/>',
                'link'  => route('coordinator.bookings') . '?status=completed'
            ],
            [
                'label' => 'Pending Bookings',
                'value' => $pendingBookings->count(),
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                'link'  => route('coordinator.bookings') . '?status=pending'
            ],
            [
                'label' => 'Upcoming Events',
                'value' => $upcomingEvents,
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"/>',
                'link'  => route('coordinator.schedule')
            ],
        ];

        return view('coordinator.dashboard', compact('pendingBookings', 'stats'));
    }

    // ================= BOOKINGS =================
    public function bookings()
    {
        // Optional: filter by status if ?status=completed/pending/upcoming
        $status = request('status');
        $query = Booking::with('client.user');

        if ($status === 'completed') {
            $query->where('status', 'completed');
        } elseif ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'upcoming') {
            $query->whereDate('booking_date', '>=', now());
        }

        $bookings = $query->orderBy('booking_date', 'desc')->paginate(10);

        return view('coordinator.bookings', compact('bookings'));
    }

    // ================= SINGLE BOOKING =================
    public function bookingsShow($id)
    {
        $booking = Booking::with('client.user')->findOrFail($id);
        return view('coordinator.bookings-show', compact('booking'));
    }
}
