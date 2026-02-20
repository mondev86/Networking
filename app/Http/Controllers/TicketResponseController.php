<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketResponse;
use Illuminate\Http\Request;

class TicketResponseController extends Controller
{
    /**
     * Store a newly created response in storage.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $user = auth()->user();

        // Verificar permisos
        if ($user->isUser() && $ticket->user_id !== $user->id) {
            abort(403, 'No tienes permiso para responder a este ticket.');
        }

        $validated = $request->validate([
            'message' => 'required|string',
            'is_internal' => 'boolean',
        ]);

        // Solo admins/superadmins pueden hacer notas internas
        $isInternal = $user->canRespondTickets() && ($validated['is_internal'] ?? false);

        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $validated['message'],
            'is_internal' => $isInternal,
        ]);

        // Si el ticket estaba cerrado, reabrirlo
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Respuesta agregada exitosamente.');
    }

    /**
     * Remove the specified response from storage.
     */
    public function destroy(TicketResponse $response)
    {
        // Solo el autor o un superadmin pueden eliminar
        if ($response->user_id !== auth()->id() && !auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $response->delete();

        return back()->with('success', 'Respuesta eliminada exitosamente.');
    }
}
