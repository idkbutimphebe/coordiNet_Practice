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
        Schema::table('reviews', function (Blueprint $table) {
            
            // Check for booking_id
            if (!Schema::hasColumn('reviews', 'booking_id')) {
                $table->foreignId('booking_id')->after('id')->constrained('bookings')->onDelete('cascade');
            }

            // Check for client_id (Adding this just in case it crashes next)
            if (!Schema::hasColumn('reviews', 'client_id')) {
                $table->foreignId('client_id')->after('booking_id')->constrained('users')->onDelete('cascade');
            }

            // Check for coordinator_id (Adding this just in case)
            if (!Schema::hasColumn('reviews', 'coordinator_id')) {
                $table->foreignId('coordinator_id')->after('client_id')->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('reviews', 'booking_id')) {
                $table->dropForeign(['booking_id']);
                $table->dropColumn('booking_id');
            }
            if (Schema::hasColumn('reviews', 'client_id')) {
                $table->dropForeign(['client_id']);
                $table->dropColumn('client_id');
            }
            if (Schema::hasColumn('reviews', 'coordinator_id')) {
                $table->dropForeign(['coordinator_id']);
                $table->dropColumn('coordinator_id');
            }
        });
    }
};