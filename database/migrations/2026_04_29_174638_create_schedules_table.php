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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('course_name');
            $table->string('lecturer');
            $table->enum('day_of_week', ['monday','tuesday','wednesday','thursday','friday','saturday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('semester'); // contoh: Ganjil 2024/2025
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
