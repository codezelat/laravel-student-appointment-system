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
        Schema::table('appointments', function (Blueprint $table) {
            // Rename requested_date to appointment_date and make it nullable since admin assigns it
            $table->renameColumn('requested_date', 'appointment_date');
            
            // Rename admin_time_slot to time_slot for better naming convention
            $table->renameColumn('admin_time_slot', 'time_slot');
        });
        
        // Make appointment_date nullable after renaming
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('appointment_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->date('appointment_date')->nullable(false)->change();
        });
        
        Schema::table('appointments', function (Blueprint $table) {
            $table->renameColumn('appointment_date', 'requested_date');
            $table->renameColumn('time_slot', 'admin_time_slot');
        });
    }
};
