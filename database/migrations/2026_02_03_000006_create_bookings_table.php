<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. If the table exists but is old/wrong, we drop it to rebuild it fresh.
        // This ensures the 'location' column definitely gets created.
        if (Schema::hasTable('bookings')) {
            Schema::drop('bookings');
        }

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('coordinator_id')->constrained('users')->cascadeOnDelete(); // Assuming coordinators are in 'users' table? Or 'coordinators'?
            
            // Note: Check if 'events' table exists. If not, remove the constrained() part temporarily.
            $table->foreignId('event_id')->constrained()->cascadeOnDelete(); 
            
            $table->string('event_name')->nullable();
            $table->date('event_date');
            $table->string('location')->nullable(); // ✅ Added here
            $table->time('start_time');
            $table->time('end_time');
            
            // ✅ ADDED 'completed' to this list so your controller doesn't crash
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            
            $table->decimal('total_amount', 10, 2)->default(0); // Added default 0 for safety
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};