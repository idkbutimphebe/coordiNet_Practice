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
        // 1. Disable Foreign Key Constraints temporarily to prevent errors during drop
        Schema::disableForeignKeyConstraints();

        // 2. Drop the table if it exists (This ensures a clean slate with the new columns)
        Schema::dropIfExists('payments');

        // 3. Re-create the table with the missing 'reference_number' column
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Relationships
            // Note: Ensure the 'bookings' table exists before this migration runs
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('coordinator_id'); // ✅ ADDED - Required for filtering payments by coordinator
            
            // Payment Details
            $table->decimal('amount', 10, 2);
            $table->date('date_paid');
            $table->string('method');
            
            // ✅ THE FIX: This creates the column you were missing
            $table->string('reference_number')->nullable(); 
            
            $table->text('notes')->nullable();
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // 4. Re-enable Constraints
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('payments');
        Schema::enableForeignKeyConstraints();
    }
};