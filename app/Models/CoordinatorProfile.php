<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinatorProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * This allows us to use CoordinatorProfile::create([...]) or $profile->update([...])
     */
    protected $fillable = [
        'user_id',      // Links this profile to the login User
        'title',        // e.g., "Professional Wedding Planner"
        'phone',        // Contact number
        'location',     // City/Address
        'bio',          // The "Expertise & Description" text area
        'rate',         // Starting price (e.g., 15000)
        'services',     // Stores the checkboxes (e.g., ['Full Planning', 'Hosting'])
        'portfolio',    // Stores image paths and descriptions
        'is_active',    // Toggle for "Accepting New Clients"
    ];

    /**
     * The attributes that should be cast to native types.
     * This is the "Magic" that handles the JSON columns.
     */
    protected $casts = [
        'services' => 'array',   // automatically converts JSON string <-> PHP Array
        'portfolio' => 'array',  // automatically converts JSON string <-> PHP Array
        'is_active' => 'boolean', // ensures 1/0 becomes true/false
        'rate' => 'decimal:2',    // ensures price is always formatted correctly
    ];

    /**
     * Relationship: A profile belongs to a single User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}