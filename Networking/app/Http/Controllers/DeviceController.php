<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Models\Team;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeviceController extends Controller
{
    /**
     * Display a listing of devices.
     */
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user && ($user->isAdmin() || $user->isSuperAdmin());

        // Si es admin/superadmin, ver TODOS los dispositivos
        if ($isAdmin) {
            $devices = Device::with('currentAssignment.assignable')
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            // Si es usuario normal, ver SOLO sus dispositivos asignados
            $userDevices = $user->devices()
                ->where('is_active', true)
                ->with('device')
                ->orderBy('assigned_date', 'desc')
                ->paginate(15);

            // Transformar para que tenga la misma estructura
            $devices = [
                'data' => $userDevices->items(),
                'links' => $userDevices->links()->elements[0] ?? [],
            ];
        }

        return Inertia::render('Equipment/Index', [
            'devices' => $devices,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * Show the form for creating a new device.
     */
    public function create()
{
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        abort(403, 'No tienes permiso para crear equipos.');
    }

    return Inertia::render('Equipment/Create', [
        'users'       => User::select('id', 'name')->orderBy('name')->get(),
        'departments' => Department::select('id', 'name')->orderBy('name')->get(),
        'teams'       => Team::select('id', 'name')->orderBy('name')->get(),
    ]);
}

    /**
     * Store a newly created device in storage.
     */
   public function store(Request $request)
{
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        abort(403, 'No tienes permiso para crear equipos.');
    }

    $validated = $request->validate([
        'serial_number'    => 'required|string|unique:devices',
        'model'            => 'required|string|max:255',
        'type'             => 'required|in:laptop,monitor,keyboard,mouse,printer,other',
        'status'           => 'required|in:available,in_use,broken,retired',
        'purchase_date'    => 'nullable|date',
        'warranty_until'   => 'nullable|date',
        'description'      => 'nullable|string',
        // Asignación opcional
        'assignable_type'  => 'nullable|in:user,team,department',
        'assignable_id'    => 'nullable|integer',
        'assignment_notes' => 'nullable|string',
    ]);

    $device = Device::create($validated);

    // ✅ Si viene asignación, procesarla
    if (!empty($validated['assignable_type']) && !empty($validated['assignable_id'])) {

        $assignableClass = match($validated['assignable_type']) {
            'user'       => User::class,
            'team'       => Team::class,
            'department' => Department::class,
        };

        $assignable = $assignableClass::findOrFail($validated['assignable_id']);

        $device->assignTo(
            $assignable,
            auth()->id(),
            $validated['assignment_notes'] ?? null
        );

        // Actualizar estado a "en uso"
        $device->update(['status' => 'in_use']);
    }

    return redirect()->route('equipment.show', $device)
        ->with('success', 'Dispositivo creado exitosamente.');
}

    /**
     * Display the specified device.
     */
    public function show(Device $device)
{
    $user = auth()->user();
    $isAdmin = $user && ($user->isAdmin() || $user->isSuperAdmin());

    if (!$isAdmin && $user) {
        $isUserDevice = $device->currentAssignment
                     && $device->currentAssignment->assignable_id === $user->id
                     && $device->currentAssignment->assignable_type === 'App\Models\User';

        if (!$isUserDevice) {
            abort(403, 'No tienes permiso para ver este equipo.');
        }
    } elseif (!$user) {
        abort(403, 'Debes estar autenticado.');
    }

    $device->load([
        'assignments.assignable',
        'assignments.assignedBy',
        'currentAssignment.assignable',
        'currentAssignment.assignedBy',
    ]);

    $assignmentHistory = $device->assignments()
        ->with(['assignable', 'assignedBy'])
        ->orderBy('assigned_date', 'desc')
        ->get();

    return Inertia::render('Equipment/Show', [
        'device'            => $device,
        'assignmentHistory' => $assignmentHistory,
        'isAdmin'           => $isAdmin,
        // ✅ Pasar usuarios como prop (igual que Create/Edit)
        'users'             => User::select('id', 'name')->orderBy('name')->get(),
    ]);
}

    /**
     * Show the form for editing the specified device.
     */
   public function edit(Device $device)
{
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        abort(403, 'No tienes permiso para editar equipos.');
    }

    return Inertia::render('Equipment/Edit', [
        'device'      => $device,
        'users'       => User::select('id', 'name')->orderBy('name')->get(),
        'departments' => Department::select('id', 'name')->orderBy('name')->get(),
        'teams'       => Team::select('id', 'name')->orderBy('name')->get(),
    ]);
}
    /**
     * Update the specified device in storage.
     */
   public function update(Request $request, Device $device)
{
    $user = auth()->user();
    if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
        abort(403, 'No tienes permiso para editar equipos.');
    }

    $validated = $request->validate([
        'serial_number'    => 'required|string|unique:devices,serial_number,' . $device->id,
        'model'            => 'required|string|max:255',
        'type'             => 'required|in:laptop,monitor,keyboard,mouse,printer,other',
        'status'           => 'required|in:available,in_use,broken,retired',
        'purchase_date'    => 'nullable|date',
        'warranty_until'   => 'nullable|date',
        'description'      => 'nullable|string',
        'assignable_type'  => 'nullable|in:user,team,department',
        'assignable_id'    => 'nullable|integer',
        'assignment_notes' => 'nullable|string',
    ]);

    $device->update($validated);

    // Procesar asignación si viene informada
    if (!empty($validated['assignable_type']) && !empty($validated['assignable_id'])) {
        $assignableClass = match($validated['assignable_type']) {
            'user'       => User::class,
            'team'       => Team::class,
            'department' => Department::class,
        };

        $assignable = $assignableClass::findOrFail($validated['assignable_id']);
        $device->assignTo($assignable, auth()->id(), $validated['assignment_notes'] ?? null);
        $device->update(['status' => 'in_use']);
    }

    return redirect()->route('equipment.show', $device)
        ->with('success', 'Dispositivo actualizado exitosamente.');
}
    /**
     * Remove the specified device from storage.
     */
    public function destroy(Device $device)
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
            abort(403, 'No tienes permiso para eliminar equipos.');
        }

        $device->delete();

        return redirect()->route('equipment.index')
            ->with('success', 'Dispositivo eliminado exitosamente.');
    }

    /**
     * Assign device to user/team/department.
     */
    public function assign(Request $request, Device $device)
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
            abort(403, 'No tienes permiso para asignar equipos.');
        }

        $validated = $request->validate([
            'assignable_type' => 'required|string|in:user,team,department',
            'assignable_id' => 'required|integer',
            'notes' => 'nullable|string',
        ]);

        $assignableClass = match($validated['assignable_type']) {
            'user' => User::class,
            'team' => Team::class,
            'department' => Department::class,
        };

        $assignable = $assignableClass::findOrFail($validated['assignable_id']);

        $device->assignTo(
            $assignable,
            auth()->id(),
            $validated['notes'] ?? null
        );

        return redirect()->route('equipment.show', $device)
            ->with('success', 'Dispositivo asignado exitosamente.');
    }

    /**
     * Unassign device.
     */
    public function unassign(Device $device)
    {
        $user = auth()->user();
        if (!$user || (!$user->isAdmin() && !$user->isSuperAdmin())) {
            abort(403, 'No tienes permiso para desasignar equipos.');
        }

        $device->unassign();

        return redirect()->route('equipment.show', $device)
            ->with('success', 'Dispositivo desasignado exitosamente.');
    }

    /**
     * Display devices assigned to a user.
     */
    public function userDevices(User $user)
    {
        $devices = $user->activeDevices()
            ->with('device')
            ->paginate(15);

        return Inertia::render('Equipment/UserDevices', [
            'user' => $user,
            'devices' => $devices,
        ]);
    }

    /**
     * Display devices assigned to a team.
     */
    public function teamDevices(Team $team)
    {
        $devices = $team->activeDevices()
            ->with('device')
            ->paginate(15);

        return Inertia::render('Equipment/TeamDevices', [
            'team' => $team,
            'devices' => $devices,
        ]);
    }

    /**
     * Get available assignment targets (users, teams, departments).
     */
    public function assignmentTargets(Device $device)
{
    $users = User::all(['id', 'name']);

    return response()->json([
        'users' => $users,
    ]);
}

    /**
     * Get assignment history in JSON.
     */
    public function getHistory(Device $device)
    {
        $history = $device->assignments()
            ->with(['assignable', 'assignedBy'])
            ->orderBy('assigned_date', 'desc')
            ->get();

        return response()->json($history);
    }
}
