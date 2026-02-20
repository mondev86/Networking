<?php

namespace Database\Seeders;

use App\Models\NetworkDevice;
use Illuminate\Database\Seeder;

class NetworkDeviceSeeder extends Seeder
{
    public function run(): void
    {
        $devices = [
            [
                'name' => 'Router Principal',
                'type' => 'router',
                'ip_address' => '192.168.1.1',
                'mac_address' => '00:11:22:33:44:55',
                'location' => 'Sala de Servidores - Rack A',
                'status' => 'available',
                'purchase_date' => '2023-01-15',
                'warranty_expiration' => '2026-01-15',
                'notes' => 'Router principal de la red',
            ],
            [
                'name' => 'Switch Core 1',
                'type' => 'switch',
                'ip_address' => '192.168.1.2',
                'mac_address' => '00:11:22:33:44:56',
                'location' => 'Sala de Servidores - Rack A',
                'status' => 'available',
                'purchase_date' => '2023-02-20',
                'warranty_expiration' => '2026-02-20',
            ],
            [
                'name' => 'Firewall Perimetral',
                'type' => 'firewall',
                'ip_address' => '192.168.1.254',
                'mac_address' => '00:11:22:33:44:57',
                'location' => 'Sala de Servidores - Rack B',
                'status' => 'available',
                'purchase_date' => '2023-03-10',
                'warranty_expiration' => '2026-03-10',
            ],
            [
                'name' => 'Access Point Planta 1',
                'type' => 'access_point',
                'ip_address' => '192.168.1.50',
                'mac_address' => '00:11:22:33:44:58',
                'location' => 'Planta 1 - Pasillo Central',
                'status' => 'assigned',
                'assigned_to' => 4, // Juan Pérez
                'purchase_date' => '2023-04-05',
                'warranty_expiration' => '2025-04-05',
            ],
            [
                'name' => 'Servidor Web Principal',
                'type' => 'server',
                'ip_address' => '192.168.1.100',
                'mac_address' => '00:11:22:33:44:59',
                'location' => 'Sala de Servidores - Rack C',
                'status' => 'available',
                'purchase_date' => '2022-12-01',
                'warranty_expiration' => '2025-12-01',
            ],
        ];

        foreach ($devices as $device) {
            NetworkDevice::create($device);
        }
    }
}
