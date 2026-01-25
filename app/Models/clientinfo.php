<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class clientinfo extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'address',
    ];
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function bookings():HasMany
    {
        return $this->hasMany(Bookings::class);
    }
    public function reviews():HasMany
    {
        return $this->hasMany(Reviews::class);
    }
}
