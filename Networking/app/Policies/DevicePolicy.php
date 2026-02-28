<?php

namespace App\Policies;

use App\Models\Device;
use App\Models\User;

class DevicePolicy
{
    /**
     * Solo admins pueden ver dispositivos
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function view(User $user, Device $device): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Solo admins pueden crear
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Solo admins pueden editar
     */
    public function update(User $user, Device $device): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Solo admins pueden eliminar
     */
    public function delete(User $user, Device $device): bool
    {
        return $user->isSuperAdmin();
    }
}
