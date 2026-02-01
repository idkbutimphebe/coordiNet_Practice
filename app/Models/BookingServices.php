<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingServices extends Model
{
    protected $fillable = [
        'booking_id',
        'service_id',
        'price',
    ];

    public function booking()
    {
        return $this->belongsTo(Bookings::class, 'booking_id');
    }

    public function service()
    {
        return $this->belongsTo(ServiceEvents::class, 'service_id');
    }
}
