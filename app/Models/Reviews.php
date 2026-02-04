<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends Model
{
    protected $fillable = [
        'booking_id',
        'client_id',
        'coordinator_id',
        'rating',
        'feedback',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'client_id'); // points to users table
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'coordinator_id'); // points to users table
    }
}
