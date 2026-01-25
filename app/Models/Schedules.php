<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedules extends Model
{
    protected $fillable = [
        'coordinator_id',
        'date',
        'start_time',
        'end_time',
        'is_available'
    ];

    public function coordinator():BelongsTo
    {
        return $this->belongsTo(CoordinatorsInfo::class,'coordinator_id');
    }
}
