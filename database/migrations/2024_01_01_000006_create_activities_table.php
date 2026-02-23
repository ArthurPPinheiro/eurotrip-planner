<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('added_by')->constrained('users')->cascadeOnDelete();
            $table->string('type'); // poi, hotel, reservation, comment
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->string('link')->nullable();
            $table->string('time')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency')->default('EUR');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('activities'); }
};
