<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'coordinator_id',
        'plan_name',
        'price',
        'start_date',
        'end_date',
        'status'
    ];

    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class,'coordinator_id');
    }
    public function payment():HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
