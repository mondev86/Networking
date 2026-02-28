<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    protected $fillable = [
        'name',
        'description',
        'manager_id',
    ];

    // ========== RELACIONES ==========

    /**
     * Dispositivos asignados a este departamento
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(DeviceAssignment::class, 'assignable');
    }

    /**
     * Dispositivos ACTIVOS del departamento
     */
    public function activeDevices()
    {
        return $this->devices()
            ->where('is_active', true)
            ->with('device');
    }

    /**
     * Gerente del departamento
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // ========== MÉTODOS ÚTILES ==========

    /**
     * Obtener cantidad de dispositivos activos
     */
    public function getActiveDeviceCount(): int
    {
        return $this->activeDevices()->count();
    }
}
