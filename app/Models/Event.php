<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'coordinator_id',
        'event_name',
        'event_type',
        'description',
        'base_price'
    ];

    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class, 'coordinator_id');
    }
    public function bookings():HasMany
    {
        return $this->hasMany(Bookings::class);
    }
}
