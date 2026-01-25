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
        'feedback'
    ];

    public function booking():BelongsTo
    {
        return $this->belongsTo(Bookings::class);
    }
    public function client():BelongsTo
    {
        return $this->belongsTo(clientinfo::class,'client_id');
    }
    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class,'coordinator_id');
    }
}
