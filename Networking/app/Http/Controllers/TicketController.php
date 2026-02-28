<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\NetworkDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index()
    {
        $user = auth()->user();

        // Si es usuario regular, solo ve sus propios tickets
        if ($user->isUser()) {
            $tickets = Ticket::with(['user', 'assignedAdmin', 'device'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Admins y superadmins ven todos los tickets
            $tickets = Ticket::with(['user', 'assignedAdmin', 'device'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Obtener dispositivos disponibles para el modal de creación
        if ($user->isUser()) {
            $devices = $user->assignedDevices()->get();
        } else {
            $devices = NetworkDevice::all();
        }

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
            'devices' => $devices
        ]);
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $user = auth()->user();

        // Obtener dispositivos del usuario (si es usuario regular)
        if ($user->isUser()) {
            $devices = $user->assignedDevices()->get();
        } else {
            $devices = NetworkDevice::all();
        }

        return Inertia::render('Tickets/Create', [
            'devices' => $devices
        ]);
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'device_id' => 'nullable|exists:network_devices,id',
        ]);

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'device_id' => $validated['device_id'] ?? null,
            'user_id' => auth()->id(),
            'status' => 'open',
        ]);

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket creado exitosamente.');
    }

    /**
     * Display the specified ticket.
     */
    public function show(Ticket $ticket)
    {
        // Verificar que el usuario tenga permiso para ver este ticket
        $user = auth()->user();
        if ($user->isUser() && $ticket->user_id !== $user->id) {
            abort(403, 'No tienes permiso para ver este ticket.');
        }

        $ticket->load(['user', 'assignedAdmin', 'device', 'responses.user']);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket
        ]);
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit(Ticket $ticket)
    {
        // Solo admins pueden editar
        if (!auth()->user()->canRespondTickets()) {
            abort(403);
        }

        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        return Inertia::render('Tickets/Edit', [
            'ticket' => $ticket->load('user', 'device'),
            'admins' => $admins
        ]);
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Solo admins pueden actualizar
        if (!auth()->user()->canRespondTickets()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $data = [
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? null,
            'priority' => $validated['priority'],
        ];

        // Si se marca como resuelto, guardar la fecha
        if ($validated['status'] === 'resolved' && $ticket->status !== 'resolved') {
            $data['resolved_at'] = now();
        }

        $ticket->update($data);

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket actualizado exitosamente.');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket)
    {
        // Solo el creador o un superadmin pueden eliminar
        if ($ticket->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket eliminado exitosamente.');
    }
}
