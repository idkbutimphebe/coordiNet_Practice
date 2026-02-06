<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\EventType; 

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        // Added Coordinator Fields:
        'title',
        'phone',
        'location',
        'bio',
        'rate',
        'is_active',
        'services',
        'event_types',
        'portfolio',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'services' => 'array',   // Cast JSON to Array
            'event_types' => 'array', // Cast JSON to Array
            'portfolio' => 'array',  // Cast JSON to Array
            'is_active' => 'boolean',
            'rate' => 'decimal:2',
        ];
    }
    // Booking
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    // Client Profile
    public function client()
    { 
        return $this->hasOne(Client::class); // Assumes you have a Client model
    }

    // ================= RELATIONSHIPS =================

    public function clientinfo(): HasOne
    {
        // Ensure the model name matches your file (e.g., ClientInfo::class)
        return $this->hasOne(ClientInfo::class); 
    }

    public function coordinatorsinfo(): HasOne
    {
        return $this->hasOne(CoordinatorsInfo::class);
    }

    public function coordinator()
{
    return $this->hasOne(Coordinator::class);
}

    public function notifications(): HasOne
    {
        return $this->hasOne(Notifications::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'coordinator_id');
    }

    // app/Models/User.php
public function eventType()
{
    return $this->belongsTo(EventType::class, 'event_type_id');
}

// Coordinator relationship
public function bookingsAsCoordinator()
{
    return $this->hasMany(Booking::class, 'coordinator_id');
}

// Client relationship
public function bookingsAsClient()
{
    return $this->hasMany(Booking::class, 'client_id');
}

public function getPhoneNumberAttribute()
    {
        // "client" refers to the public function client() { return $this->hasOne(Client::class); }
        return $this->client->phone_number ?? null; 
    }
}