<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'ip_address',
        'mac_address',
        'location',
        'status',
        'assigned_to',
        'purchase_date',
        'warranty_expiration',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
            'warranty_expiration' => 'date',
        ];
    }

    // Relaciones
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'device_id');
    }

    // Scopes útiles
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }
}
