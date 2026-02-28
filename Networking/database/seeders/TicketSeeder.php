<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;
use App\Models\NetworkDevice;
use Illuminate\Support\Facades\DB;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Ejemplo: crear algunos tickets de prueba
        $user = User::first(); // primer usuario
        $device = NetworkDevice::first(); // primer dispositivo

        if ($user && $device) {
            Ticket::create([
                'title' => 'Problema de conexión',
                'description' => 'El dispositivo no se conecta a la red.',
                'status' => 'open',
                'user_id' => $user->id,
                'device_id' => $device->id,
                'assigned_to' => $user->id, // opcional si usas esta columna
            ]);

            Ticket::create([
                'title' => 'Actualización de firmware',
                'description' => 'El dispositivo necesita actualización.',
                'status' => 'in_progress',
                'user_id' => $user->id,
                'device_id' => $device->id,
            ]);
        }
    }
}
