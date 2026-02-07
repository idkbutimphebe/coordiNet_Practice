<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\Service;
use App\Models\User; // ✅ Added User model
use App\Models\Coordinator;
use App\Models\Payment;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $casts = [
        'event_date' => 'date',
    ];

    protected $fillable = [
        'client_id',
        'coordinator_id', // ✅ Make sure this is fillable if you create bookings
        'event_id',
        'event_name', 
        'event_date',
        'location',
        'start_time',
        'end_time',
        'status',
        'total_amount',
        'note'
    ];

    /* ================= RELATIONSHIPS ================= */

// Link to the client (user with role='client')
public function client()
{
    return $this->belongsTo(User::class, 'client_id');
    
}

// Link to the coordinator (Coordinator model, not User)
public function coordinator()
{
    return $this->belongsTo(Coordinator::class, 'coordinator_id');
}

// Link to the event
public function event()
{
    return $this->belongsTo(Event::class, 'event_id');
}

// Link to services
public function services()
{
    return $this->belongsToMany(Service::class, 'booking_services')
        ->withPivot('quantity', 'price')
        ->withTimestamps();
}
public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /* ================= PAYMENT ACCESSORS ================= */

    /**
     * Get total amount paid for this booking
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount') ?? 0;
    }

    /**
     * Get remaining balance for this booking
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    /**
     * Get payment status: unpaid, partial, or paid
     */
    public function getPaymentStatusAttribute()
    {
        if ($this->total_paid == 0) {
            return 'unpaid';
        }
        if ($this->total_paid >= $this->total_amount) {
            return 'paid';
        }
        return 'partial';
    }

    /**
     * Get payment percentage
     */
    public function getPaymentPercentageAttribute()
    {
        if ($this->total_amount == 0) {
            return 0;
        }
        return round(($this->total_paid / $this->total_amount) * 100, 2);
    }
    public function reviews()
    {
        // This connects Booking to your Reviews model
        return $this->hasOne(Reviews::class, 'booking_id');
    }
}