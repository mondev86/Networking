<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $fillable = [
        'serial_number',
        'model',
        'type',
        'status',
        'purchase_date',
        'warranty_until',
        'description',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_until' => 'date',
    ];

    // ========== RELACIONES ==========

    /**
     * Obtener todas las asignaciones de este dispositivo
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(DeviceAssignment::class);
    }

    /**
     * Obtener la asignación ACTUAL (activa)
     */
    public function currentAssignment()
    {
        return $this->hasOne(DeviceAssignment::class)
            ->where('is_active', true)
            ->latest('assigned_date');
    }

    /**
     * Obtener el usuario/equipo/departamento actual que tiene el dispositivo
     */
    public function getCurrentAssignee()
    {
        $current = $this->currentAssignment;
        return $current ? $current->assignable : null;
    }

    // ========== MÉTODOS ÚTILES ==========

    /**
     * Verificar si el dispositivo está disponible
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && !$this->currentAssignment;
    }

    /**
     * Obtener tipo de asignatario actual (user, team, department)
     */
    public function getCurrentAssigneeType(): ?string
    {
        $assignment = $this->currentAssignment;
        if (!$assignment) return null;

        return match($assignment->assignable_type) {
            'App\\Models\\User' => 'user',
            'App\\Models\\Team' => 'team',
            'App\\Models\\Department' => 'department',
            default => null,
        };
    }

    /**
     * Asignar dispositivo a una entidad (User, Team, Department)
     */
    public function assignTo($assignable, $assignedBy = null, $notes = null)
    {
        // Desactivar asignación anterior
        $this->assignments()
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'unassigned_date' => now(),
            ]);

        // Crear nueva asignación
        return $this->assignments()->create([
            'assignable_type' => get_class($assignable),
            'assignable_id' => $assignable->id,
            'assigned_by' => $assignedBy ?? auth()->id(),
            'assigned_date' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Desasignar dispositivo
     */
    public function unassign()
    {
        return $this->assignments()
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'unassigned_date' => now(),
            ]);
    }

    /**
     * Obtener historial completo de asignaciones
     */
    public function getHistory()
    {
        return $this->assignments()
            ->with(['assignable', 'assignedBy'])
            ->orderBy('assigned_date', 'desc')
            ->get();
    }
}
