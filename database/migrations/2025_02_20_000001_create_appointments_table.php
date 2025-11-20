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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_id');
            $table->string('full_name');
            $table->string('branch'); // Colombo, Gampola
            $table->json('purpose'); // collect true Copy, collect certificate, collect photos
            $table->date('requested_date');
            $table->string('admin_time_slot')->nullable(); // Admin assigns this
            $table->string('status')->default('pending'); // pending, approved
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
