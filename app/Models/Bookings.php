<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bookings extends Model
{
    protected $fillable = [
        'client_id',
        'coordinator_id',
        'event_id',
        'booking_date',
        'status',
        'total_price'
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(clientinfo::class, 'client_id');
    }
    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class, 'coordinator_id');
    }
    public function event():BelongsTo
    {
        return $this->belongsTo(Event::class);
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
    public function reveiw():HasOne
    {
        return $this->hasOne(Reviews::class);
    }

}
