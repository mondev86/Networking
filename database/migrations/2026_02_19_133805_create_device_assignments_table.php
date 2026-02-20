<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_assignments', function (Blueprint $table) {
            $table->id();

            // ===== VINCULACIÓN AL DISPOSITIVO =====
            $table->foreignId('device_id')
                ->constrained('devices')
                ->cascadeOnDelete();

            // ===== CAMPOS POLIMÓRFICOS =====
            // Permite asignar a User, Team, Department, etc
            $table->string('assignable_type');      // Ej: 'App\Models\User'
            $table->unsignedBigInteger('assignable_id');  // ID del usuario/equipo/departamento

            // ===== AUDITORÍA =====
            $table->foreignId('assigned_by')        // Quién asignó el dispositivo
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamp('assigned_date')->useCurrent();    // Cuándo se asignó
            $table->timestamp('unassigned_date')->nullable();    // Cuándo se desasignó
            $table->boolean('is_active')->default(true);         // ¿Está asignado actualmente?

            // ===== INFORMACIÓN ADICIONAL =====
            $table->text('notes')->nullable();      // Notas sobre la asignación

            // ===== TIMESTAMPS =====
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ===== ÍNDICES PARA BÚSQUEDAS RÁPIDAS =====
            $table->index('device_id');
            $table->index(['assignable_type', 'assignable_id']);
            $table->index('is_active');
            $table->index('assigned_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_assignments');
    }
};
