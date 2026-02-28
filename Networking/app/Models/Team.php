<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Team extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
    ];

    // ========== RELACIONES ==========

    /**
     * El usuario que creó el equipo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Usuarios que pertenecen a este equipo
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    /**
     * Dispositivos asignados a este equipo
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(DeviceAssignment::class, 'assignable');
    }

    /**
     * Dispositivos ACTIVOS del equipo
     */
    public function activeDevices()
    {
        return $this->devices()
            ->where('is_active', true)
            ->with('device');
    }

    // ========== MÉTODOS ÚTILES ==========

    /**
     * Obtener cantidad de dispositivos activos
     */
    public function getActiveDeviceCount(): int
    {
        return $this->activeDevices()->count();
    }

    /**
     * Agregar usuario al equipo
     */
    public function addUser(User $user)
    {
        return $this->users()->attach($user->id);
    }

    /**
     * Remover usuario del equipo
     */
    public function removeUser(User $user)
    {
        return $this->users()->detach($user->id);
    }

    /**
     * Verificar si un usuario pertenece al equipo
     */
    public function hasUser(User $user): bool
    {
        return $this->users->contains($user);
    }
}
