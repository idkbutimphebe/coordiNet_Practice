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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coordinator_id')->constrained('coordinators_infos')->onDelete('cascade');
            $table->string('event_name')->nullable();
            $table->string('event_type')->nullable();
            $table->text('description')->nullable();
            $table->decimal('base_price', 8, 2);
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
