<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    // ERROR LOCATION: If this list is missing or incomplete, saving FAILS.
    protected $fillable = [
        'coordinator_id',
        'name',
        'date',
        'start_time',
        'end_time',
        'location'
    ];

    // Relationship to User
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }
}