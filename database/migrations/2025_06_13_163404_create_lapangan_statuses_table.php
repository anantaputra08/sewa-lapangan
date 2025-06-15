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
        Schema::create('lapangan_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lapangan_id')->constrained('lapangans')->onDelete('cascade');
            $table->enum('status', ['available', 'booked','unavailable'])->default('available');
            $table->date('date')->nullable();
            $table->json('session_hour_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lapangan_statuses');
    }
};
