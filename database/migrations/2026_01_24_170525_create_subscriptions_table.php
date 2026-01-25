<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coordinator_id')
                  ->constrained('coordinators_infos') 
                  ->cascadeOnDelete();

            $table->string('plan_name')->nullable();
            $table->decimal('price', 8, 2); 
            $table->date('start_date');
            $table->date('end_date');

            $table->enum('status', ['active', 'expired', 'cancelled']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
