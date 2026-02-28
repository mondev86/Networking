<?php

namespace App\Http\Controllers;

use App\Models\NetworkDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NetworkDeviceController extends Controller
{
    /**
     * Display a listing of network devices.
     */
    public function index()
    {
        $devices = NetworkDevice::with('assignedUser')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('NetworkDevices/Index', [
            'devices' => $devices
        ]);
    }

    /**
     * Show the form for creating a new device.
     */
    public function create()
    {
        $users = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        return Inertia::render('NetworkDevices/Create', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created device in storage.
     */
    // app/Http/Controllers/NetworkDeviceController.php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:router,switch,firewall,access_point,server',
        'ip_address' => 'required|ip|unique:network_devices',
        'mac_address' => 'nullable|string|max:17',
        'location' => 'nullable|string|max:255',
        'status' => 'required|in:available,assigned,maintenance,offline',
    ]);

    NetworkDevice::create($validated);

    return redirect()->route('devices.index')
        ->with('success', 'Dispositivo creado exitosamente.');
}

    /**
     * Display the specified device.
     */
    public function show($deviceId)
    {
        $device = NetworkDevice::find($deviceId);

        if (!$device) {
            return redirect()->route('devices.index')->with('error', 'Dispositivo no encontrado.');
        }

        $device->load(['assignedUser', 'tickets.user']);

        return Inertia::render('NetworkDevices/Show', [
            'device' => $device
        ]);
    }

    /**
     * Show the form for editing the specified device.
     */
    public function edit($deviceId)
    {
        $device = NetworkDevice::find($deviceId);

        if (!$device) {
            return redirect()->route('devices.index')->with('error', 'Dispositivo no encontrado.');
        }

        $users = User::where('role', 'user')
            ->orderBy('name')
            ->get();

        return Inertia::render('NetworkDevices/Edit', [
            'device' => $device,
            'users' => $users
        ]);
    }

    /**
     * Update the specified device in storage.
     */
    public function update(Request $request, $deviceId)
    {
        $device = NetworkDevice::find($deviceId);

        if (!$device) {
            return redirect()->route('devices.index')->with('error', 'Dispositivo no encontrado.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:router,switch,firewall,access_point,server',
            'ip_address' => 'nullable|ip',
            'mac_address' => 'nullable|string|max:17',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:online,offline,maintenance',
            'assigned_to' => 'nullable|exists:users,id',
            'purchase_date' => 'nullable|date',
            'warranty_expiration' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $device->update($validated);

        return redirect()->route('devices.index')
            ->with('success', 'Dispositivo actualizado exitosamente.');
    }

    /**
     * Remove the specified device from storage.
     */
    public function destroy($deviceId)
    {
        $device = NetworkDevice::find($deviceId);

        if (!$device) {
            return redirect()->route('devices.index')->with('error', 'Dispositivo no encontrado.');
        }

        $device->delete();

        return redirect()->route('devices.index')
            ->with('success', 'Dispositivo eliminado exitosamente.');
    }

    /**
     * Display available devices (for dashboard).
     */
    public function available()
    {
        $devices = NetworkDevice::where('status', 'available')->get();

        return Inertia::render('NetworkDevices/Available', [
            'devices' => $devices
        ]);
    }
}
