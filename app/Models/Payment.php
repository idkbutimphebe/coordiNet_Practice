<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    // Explicitly define table name if Laravel is getting confused
    protected $table = 'payments';

    protected $fillable = [
        'booking_id',
        'coordinator_id', 
        'subscription_id',
        'amount',
        'date_paid',
        'method',
        'notes',
        'status' 
    ];

    // This converts the string date from the database into a Carbon object automatically
    protected $casts = [
        'date_paid' => 'date',
        'amount' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class, 'coordinator_id'); 
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}