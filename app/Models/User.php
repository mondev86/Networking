<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\NetworkDevice[] $assignedDevices
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ticket[] $tickets
 *
 * @method bool isSuperAdmin()
 * @method bool isAdmin()
 */

/**
 * @method bool isSuperAdmin()
 * @method bool isAdmin()
 * @method bool isUser()
 * @method bool canManageInventory()
 * @method bool canManageUsers()
 * @method bool canRespondTickets()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany assignedDevices()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany tickets()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany assignedTickets()
 * @method \Illuminate\Database\Eloquent\Relations\HasMany ticketResponses()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ========== RELACIONES EXISTENTES ==========

    public function assignedDevices()
    {
        return $this->hasMany(NetworkDevice::class, 'assigned_to');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }

    public function ticketResponses()
    {
        return $this->hasMany(TicketResponse::class);
    }

    // ========== RELACIONES DE EQUIPOS ==========

    /**
     * Equipos que el usuario ha creado
     */
    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    /**
     * Equipos a los que el usuario pertenece
     */
    public function memberOf(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    // ========== RELACIONES DE DISPOSITIVOS INFORMATICOS ==========

    /**
     * Dispositivos (impresoras, monitores, etc) asignados a este usuario
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(DeviceAssignment::class, 'assignable');
    }

    /**
     * Dispositivos ACTIVOS de este usuario
     */
    public function activeDevices()
    {
        return $this->devices()
            ->where('is_active', true)
            ->with('device');
    }

    /**
     * Historial completo de dispositivos
     */
    public function deviceHistory()
    {
        return $this->devices()
            ->with('device')
            ->orderBy('assigned_date', 'desc');
    }

    /**
     * Obtener cantidad de dispositivos activos
     */
    public function getActiveDeviceCount(): int
    {
        return $this->activeDevices()->count();
    }

    // ========== HELPER METHODS PARA ROLES ==========

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function canManageInventory()
    {
        return $this->isSuperAdmin();
    }

    public function canManageUsers()
    {
        return $this->isSuperAdmin();
    }

    public function canRespondTickets()
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }
}
