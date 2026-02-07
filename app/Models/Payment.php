<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

protected $fillable = [
        'booking_id',
        'coordinator_id', // ✅ ADDED - Allows mass assignment when creating payments
        'amount', 
        'date_paid', 
        'method', 
        'status',
        'notes',
        'reference_number', // <--- ADD THIS LINE
    ];

    protected $casts = [
        'date_paid' => 'date',
        'amount' => 'decimal:2',
    ];

    // ============ RELATIONSHIPS ============

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

    // ============ SCOPE METHODS ============

    /**
     * Filter by Coordinator ID
     */
    public function scopeByCoordinator($query, $coordinatorId)
    {
        return $query->where('coordinator_id', $coordinatorId);
    }

    /**
     * Filter by Date Range
     */
    public function scopeByDateRange($query, $dateFrom, $dateTo)
    {
        if ($dateFrom) {
            $query->whereDate('date_paid', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date_paid', '<=', $dateTo);
        }
        return $query;
    }

    /**
     * Filter by Payment Method
     */
    public function scopeByMethod($query, $method)
    {
        if ($method) {
            $query->where('method', $method);
        }
        return $query;
    }

    /**
     * Filter by Status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Search by Client Name, Event Name, or Reference Number
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                // Search Client Name
                $q->whereHas('booking.client', fn($c) => $c->where('name', 'like', "%$search%"))
                  
                  // Search Reference Number
                  ->orWhere('reference_number', 'like', "%$search%")
                  
                  // Search Event Name (Check both Booking table column AND related Event model)
                  ->orWhereHas('booking', function($b) use ($search) {
                      $b->where('event_name', 'like', "%$search%") // If event name is on bookings table
                        ->orWhereHas('event', fn($e) => $e->where('name', 'like', "%$search%")); // If using Event model
                  });
            });
        }
        return $query;
    }

    // ============ HELPER METHODS ============
    
    /**
     * Get formatted amount with currency (Accessor)
     * Usage: $payment->formatted_amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₱' . number_format($this->amount, 2);
    }

    /**
     * Get badge color based on payment method (Accessor)
     * Usage: $payment->method_badge_color
     */
    public function getMethodBadgeColorAttribute()
    {
        return match($this->method) {
            'cash' => 'bg-green-100 text-green-800',
            'gcash' => 'bg-blue-100 text-blue-800',
            'bank' => 'bg-purple-100 text-purple-800',
            'check' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get badge color based on status (Accessor)
     * Usage: $payment->status_badge_color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'completed', 'paid' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed', 'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}