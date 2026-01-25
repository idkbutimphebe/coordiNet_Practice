<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoordinatorsInfo extends Model
{
    protected $fillable = [
        'user_id',
        'business_name',
        'bio',
        'expertise',
        'years_of_experience',
        'rating_avg',
        'is_verified',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function subscriptions():HasMany
    {
        return $this->hasMany(Subscription::class,'coordinator_id');
    }
    public function payments():HasMany
    {
        return $this->hasMany(Payment::class,'coordinator_id');
    }
    public function events():HasMany
    {
        return $this->hasMany(Event::class,'coordinator_id');
    }
    public function schedules():HasMany
    {
        return $this->hasMany(Schedules::class,'coordinator_id');
    }
    public function bookings():HasMany
    {
        return $this->hasMany(Bookings::class,'coordinator_id');
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Reviews::class,'coordinator_id');
    }

}
