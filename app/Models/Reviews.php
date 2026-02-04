<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reviews extends Model
{
    use HasFactory;

    // Allow mass assignment
    protected $fillable = [
        'client_id',
        'coordinator_id',
        'booking_id',
        'rating',
        'feedback',
    ];

    /**
     * The booking this review belongs to
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * The client who wrote the review
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'client_id'); // points to users table
    }

    /**
     * The coordinator this review is for
     */
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'coordinator_id'); // points to users table
    }
}
