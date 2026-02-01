<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB'; 
            $table->id();
            
            // FIX: Use foreignId() with constrained() for cleaner, error-free code
            
            // 1. Link to Bookings Table
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');

            // 2. Link to Users Table (For the Client)
            // Changed 'clientinfos' -> 'users'
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');

            // 3. Link to Users Table (For the Coordinator)
            // Changed 'coordinatorsinfos' -> 'users'
            $table->foreignId('coordinator_id')->constrained('users')->onDelete('cascade');

            $table->tinyInteger('rating'); // 1-5 stars
            $table->text('feedback')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};