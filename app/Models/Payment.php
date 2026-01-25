<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'coordinator_id',
        'subscription_id',
        'amount',
        'gcash_reference',
        'payment_method',
        'status'
    ];

    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class);
    }
    public function subscription():BelongsTo
    {
        return $this->belongsTo(Subscription::class,'subscription_id');
    }
}
