<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coordinators_infos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('business_name');
            $table->string('phone_number')->nullable();
            $table->text('bio')->nullable();
            $table->text('expertise')->nullable();
            $table->integer('years_of_experience');

            $table->decimal('rating_avg', 3, 1); // e.g. 9.9, 10.0
            $table->boolean('is_verified')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordinators_infos');
    }
};
