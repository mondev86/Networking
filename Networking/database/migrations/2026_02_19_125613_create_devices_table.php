<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración (crear tabla)
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            // ========== IDENTIFICADOR ==========
            $table->id();  // BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT

            // ========== CAMPOS PRINCIPALES ==========
            $table->string('serial_number')->unique();  // VARCHAR(255) UNIQUE
            $table->string('model');                     // VARCHAR(255)

            // ========== ENUMS (Valores fijos) ==========
            $table->enum('type', [
                'laptop',
                'monitor',
                'keyboard',
                'mouse',
                'printer',
                'other'
            ]);

            $table->enum('status', [
                'available',
                'in_use',
                'broken',
                'retired'
            ])->default('available');

            // ========== FECHAS OPCIONALES ==========
            $table->date('purchase_date')->nullable();
            $table->date('warranty_until')->nullable();

            // ========== DESCRIPCIÓN ==========
            $table->text('description')->nullable();

            // ========== TIMESTAMPS (IMPORTANTE) ==========
            // useCurrent() = se rellena automáticamente con la hora actual
            // useCurrentOnUpdate() = se actualiza automáticamente si cambias el registro
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // ========== ÍNDICES (Para búsquedas rápidas) ==========
            $table->index('serial_number');
            $table->index('status');
        });
    }

    /**
     * Revertir la migración (eliminar tabla)
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
