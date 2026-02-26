<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('day_id')->constrained()->cascadeOnDelete();
            $table->string('flight_number')->nullable();
            $table->string('airline')->nullable();
            $table->string('locator')->nullable();
            $table->string('departure_airport');
            $table->string('departure_city')->nullable();
            $table->string('arrival_airport');
            $table->string('arrival_city')->nullable();
            $table->string('departure_time')->nullable();
            $table->string('arrival_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('seat')->nullable();
            $table->string('cabin_class')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
