<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'coordinator_id',
        'service_name',
        'description',
        'duration_hours',
        'is_active'
    ];

    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_services')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
