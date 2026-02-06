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
    Schema::disableForeignKeyConstraints();

    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('booking_id');
        $table->unsignedBigInteger('coordinator_id')->nullable();
        $table->unsignedBigInteger('subscription_id')->nullable();
        $table->decimal('amount', 10, 2);
        $table->date('date_paid');
        $table->string('method');
        $table->text('notes')->nullable();
        $table->string('status')->default('completed');
        $table->timestamps();
    });

    Schema::enableForeignKeyConstraints();
}
};