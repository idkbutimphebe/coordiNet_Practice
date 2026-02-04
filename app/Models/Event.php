<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Event extends Model
{
    protected $fillable = [
        'coordinator_id',
        'event_name',
        'event_type',
        'description',
    ];


public function coordinator(): BelongsTo
{
    return $this->belongsTo(User::class, 'coordinator_id');
}

    public function bookings():HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
