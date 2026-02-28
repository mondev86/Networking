<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DeviceAssignment extends Model
{
    protected $fillable = [
        'device_id',
        'assignable_type',
        'assignable_id',
        'assigned_by',
        'assigned_date',
        'unassigned_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'unassigned_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ========== RELACIONES ==========

    /**
     * El dispositivo que fue asignado
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * El usuario que realizó la asignación
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Relación polimórfica: puede ser User, Team, Department, etc
     */
    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    // ========== MÉTODOS ÚTILES ==========

    /**
     * Obtener el nombre del asignatario
     */
    public function getAssigneeName(): string
    {
        return $this->assignable->name ?? 'Desconocido';
    }

    /**
     * Obtener la duración de la asignación en días
     */
    public function getDurationDays(): int
    {
        $endDate = $this->unassigned_date ?? now();
        return $this->assigned_date->diffInDays($endDate);
    }

    /**
     * Obtener tipo de asignatario
     */
    public function getAssignableType(): string
    {
        return class_basename($this->assignable_type);
    }
}
