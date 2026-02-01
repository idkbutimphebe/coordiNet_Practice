<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // We check if columns exist to prevent errors if you run this twice
            if (!Schema::hasColumn('users', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
            if (!Schema::hasColumn('users', 'rate')) {
                $table->decimal('rate', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('users', 'services')) {
                $table->json('services')->nullable();
            }
            if (!Schema::hasColumn('users', 'portfolio')) {
                $table->json('portfolio')->nullable(); 
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            // Adding avatar here just in case it's missing
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'phone', 'location', 'bio', 'rate', 
                'services', 'portfolio', 'is_active', 'avatar'
            ]);
        });
    }
};