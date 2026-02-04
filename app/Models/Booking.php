<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Service;
use App\Models\User; // ✅ Added User model

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'client_id',
        'coordinator_id', // ✅ Make sure this is fillable if you create bookings
        'event_id',
        'event_name', 
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'total_amount',
        'note'
    ];

    /* ================= RELATIONSHIPS ================= */

    // Link to the client (user with role='client')
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Link to the coordinator (user with role='coordinator')
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    // Link to the event
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // Link to services
    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
