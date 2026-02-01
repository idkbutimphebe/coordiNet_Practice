<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Event;
use App\Models\Service;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'client_id',
        'event_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'total_amount',
        'note'
    ];

    /* ================= RELATIONSHIPS ================= */

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
    public function coordinator()
{
    return $this->belongsTo(Coordinator::class);
}

}
