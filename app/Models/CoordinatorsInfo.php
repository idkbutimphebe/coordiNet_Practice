<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoordinatorsInfo extends Model
{
    protected $table = 'coordinators_infos';

    protected $fillable = [
        'user_id',
        'business_name',
        'bio',
        'expertise',
        'years_of_experience',
        'rating_avg',
        'is_verified',
    ];
    protected $casts = [
    'is_verified' => 'boolean',
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        // 🔥 MUST MATCH THE MODEL NAME EXACTLY
        return $this->hasMany(Reviews::class, 'coordinator_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'coordinator_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'coordinator_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'coordinator_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'coordinator_id'); // singular
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'coordinator_id'); // singular
    }


}
