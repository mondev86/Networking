export interface User {
    id: number;
    name: string;
    email: string;
    role: 'user' | 'admin' | 'superadmin';
    email_verified_at?: string;
    created_at: string;
    updated_at: string;
}

export interface PageProps {
    auth: {
        user: User;
    };
    flash?: {
        success?: string;
        error?: string;
    };
}

export interface DashboardStats {
    total_users: number;
    available_devices: number;
    open_tickets: number;
    my_assigned_tickets: number;
    my_devices: number;
    my_tickets: number;
    my_open_tickets: number;
}

export interface NetworkDevice {
    id: number | string;
    name: string;
    type: 'router' | 'switch' | 'firewall' | 'server' | 'printer' | 'camera' | 'other';
    ip_address: string;
    mac_address?: string;
    location?: string;
    status: 'online' | 'offline' | 'maintenance';
    description?: string;
    owner_id?: number;
    created_at: string;
    updated_at: string;
}

export interface Ticket {
    id: number | string;
    title: string;
    description: string;
    status: 'open' | 'in_progress' | 'closed' | 'on_hold';
    priority: 'low' | 'medium' | 'high' | 'critical';
    device_id?: number;
    user: User;
    assigned_to?: User;
    created_at: string;
    updated_at: string;
}

export interface TicketResponse {
    id: number;
    ticket_id: number;
    user_id: number;
    response: string;
    user: User;
    created_at: string;
    updated_at: string;
}
