<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bookings extends Model
{
    protected $fillable = [
        'name',
        'event',
        'status',
    ];


    public function client():BelongsTo
    {
        return $this->belongsTo(clientinfo::class, 'client_id');
    }
    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class, 'coordinator_id');
    }
        public function bookingServices(): HasMany
    {
        return $this->hasMany(BookingServices::class, 'booking_id');
    }

    /**
     * Optional review (if reviews table exists with booking_id)
     */
    public function eventInfo(): BelongsTo
{
    return $this->belongsTo(Event::class, 'event_id');
}
     public function services()
    {
        return $this->belongsToMany(
            ServiceEvents::class,
            'booking_services',
            'booking_id',
            'service_id'
        )->withPivot('price')->withTimestamps();
    }
    public function review(): HasOne
    {
        return $this->hasOne(Reviews::class, 'booking_id');
    }

}
