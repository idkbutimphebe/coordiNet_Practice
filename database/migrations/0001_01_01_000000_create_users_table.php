<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('client');
            
            // --- NEW PROFILE FIELDS (Added these) ---
            $table->string('title')->nullable();        // Job Title
            $table->string('phone')->nullable();        // Phone Number
            $table->string('location')->nullable();     // City/Address
            $table->text('bio')->nullable();            // Description
            $table->decimal('rate', 10, 2)->nullable(); // Price/Rate
            $table->boolean('is_active')->default(true); // Availability
            
            // --- JSON FIELDS for Checkboxes ---
            $table->json('services')->nullable();       // Stores ["Full Planning", "Day-of"]
            $table->json('event_types')->nullable();    // Stores ["Wedding", "Debut"]
            $table->json('portfolio')->nullable();      // Stores image paths
            $table->string('avatar')->nullable();       // Profile picture
            
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};