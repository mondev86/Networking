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
        Schema::create('network_devices', function (Blueprint $table) {
            $table->id();
             $table->string('name');
            $table->enum('type', ['router', 'switch', 'firewall', 'access_point', 'server']);
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'assigned', 'maintenance', 'offline'])->default('available');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiration')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_devices');
    }
};
