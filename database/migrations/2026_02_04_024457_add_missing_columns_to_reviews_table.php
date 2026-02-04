<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->after('id');
            $table->unsignedBigInteger('client_id')->after('booking_id');
            $table->unsignedBigInteger('coordinator_id')->after('client_id');
            
            // Optional: add foreign keys if you want
            // $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            // $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('coordinator_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['booking_id', 'client_id', 'coordinator_id']);
        });
    }
};
