<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, expand the enum to include all possible values
        DB::statement("ALTER TABLE network_devices MODIFY COLUMN status ENUM('available', 'assigned', 'maintenance', 'offline', 'online') DEFAULT 'online'");

        // Then update any invalid status values to 'online'
        DB::statement("UPDATE network_devices SET status = 'online' WHERE status NOT IN ('online', 'offline', 'maintenance')");

        // Finally, restrict the enum to the correct values
       DB::statement("ALTER TABLE network_devices MODIFY COLUMN status ENUM('available', 'assigned', 'maintenance', 'offline', 'online') DEFAULT 'online'");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('network_devices', function (Blueprint $table) {
            //
        });
    }
};
