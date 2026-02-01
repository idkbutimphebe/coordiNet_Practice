<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
            'portfolio' => 'array',  // Cast JSON to Array
            'is_active' => 'boolean',
            'rate' => 'decimal:2',
        ];
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

    public function notifications(): HasOne
    {
        return $this->hasOne(Notifications::class);
    }
}