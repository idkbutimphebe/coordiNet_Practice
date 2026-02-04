<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceEvents extends Model
{
    protected $fillable = [
        'coordinator_id',
        'service_name',
        'description',
        'price',
        'duration_hours',
        'is_active'
    ];

    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class, 'coordinator_id');
    }
    public function bookings():BelongsToMany
    {
        return $this->belongsToMany(
            Bookings::class,
            'booking_services',
            'service_id',
            'booking_id'
        )->withPivot('price')->withTimestamps();
    }
}
