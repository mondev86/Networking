<?php

namespace App\Http\Controllers;

use App\Models\NetworkDevice;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $stats = [
                'total_users' => User::count(),
                'available_devices' => NetworkDevice::where('status', 'available')->count(),
                'open_tickets' => Ticket::whereIn('status', ['open', 'in_progress'])->count(),
            ];

            $recentDevices = NetworkDevice::latest()->take(5)->get();
            $recentTickets = Ticket::with('user')->latest()->take(5)->get();

        } elseif ($user->isAdmin()) {
            $stats = [
                'available_devices' => NetworkDevice::where('status', 'available')->count(),
                'open_tickets' => Ticket::whereIn('status', ['open', 'in_progress'])->count(),
                'my_assigned_tickets' => Ticket::where('assigned_to', $user->id)->where('status', '!=', 'closed')->count(),
            ];

            $recentDevices = NetworkDevice::latest()->take(5)->get();
            $recentTickets = Ticket::with('user')->latest()->take(5)->get();

        } else {
            $stats = [
                'my_devices' => NetworkDevice::where('owner_id', $user->id)->count(),
                'my_tickets' => Ticket::where('user_id', $user->id)->count(),
                'my_open_tickets' => Ticket::where('user_id', $user->id)->whereIn('status', ['open', 'in_progress'])->count(),
            ];

            $recentDevices = NetworkDevice::where('owner_id', $user->id)->latest()->take(5)->get();
            $recentTickets = Ticket::where('user_id', $user->id)->with('user')->latest()->take(5)->get();
        }

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentDevices' => $recentDevices,
            'recentTickets' => $recentTickets,
        ]);
    }

}
