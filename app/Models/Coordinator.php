<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coordinator extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'coordinators';

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'coordinator_name', // Name of the coordinator
        'event_type',       // e.g., 'wedding', 'birthday', 'others'
        'expertise',        // Optional, skills or specialties
        'phone_number',
        'address',
        'status',           // 'active', 'pending', 'suspended', etc.
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Link coordinator to user account
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Coordinator can have many events (optional)
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // Coordinator can provide many services
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Coordinator schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // ================= FIXED RELATIONSHIPS =================
    
    // Coordinator can have many bookings
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'coordinator_id');
    }

    // Coordinator can have many ratings/reviews
    public function ratings()
    {
        return $this->hasMany(\App\Models\Reviews::class, 'coordinator_id');
    }

    // Coordinator can have many payments
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'coordinator_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    // Scope for filtering by event type
    public function scopeEventType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    // Scope for active coordinators
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for pending coordinators
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Optional: Get coordinator's full display name
    public function getDisplayNameAttribute()
    {
        return $this->coordinator_name;
    }

    // Get total income from all payments
    public function getTotalIncomeAttribute()
    {
        return $this->payments()->sum('amount') ?? 0;
    }

    // Get count of completed bookings
    public function getCompletedBookingsCountAttribute()
    {
        return $this->bookings()->where('status', 'completed')->count();
    }

    // Get average payment value
    public function getAveragePaymentValueAttribute()
    {
        $totalPayments = $this->payments()->count();
        if ($totalPayments == 0) {
            return 0;
        }
        return round($this->total_income / $totalPayments, 2);
    }


}
