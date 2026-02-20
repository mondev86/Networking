export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    role: 'user' | 'admin' | 'superadmin';
    two_factor_secret?: string | null;
    two_factor_recovery_codes?: string | null;
    current_team_id?: number | null;
    profile_photo_path?: string | null;
    created_at: string;
    updated_at: string;
}

export interface NetworkDevice {
    id: number;
    name: string;
    type: 'router' | 'switch' | 'firewall' | 'access_point' | 'server';
    ip_address: string | null;
    mac_address: string | null;
    location: string | null;
    status: 'available' | 'assigned' | 'maintenance' | 'offline';
    assigned_to: number | null;
    purchase_date: string | null;
    warranty_expiration: string | null;
    notes: string | null;
    created_at: string;
    updated_at: string;
    assignedUser?: {
        id: number;
        name: string;
        email: string;
    };
    tickets?: any[];
}

export interface TicketResponse {
    id: number;
    message: string;
    is_internal: boolean;
    user: {
        id: number;
        name: string;
        email: string;
    };
    user_id: number;
    created_at: string;
    updated_at: string;
}

export interface Ticket {
    id: number;
    title: string;
    description: string;
    priority: 'low' | 'medium' | 'high' | 'urgent';
    status: 'open' | 'in_progress' | 'resolved' | 'closed';
    user: {
        id: number;
        name: string;
        email: string;
    };
    user_id: number;
    device?: {
        id: number;
        name: string;
        type: string;
    } | null;
    assigned_to?: number | null;
    assigned_admin?: {
        id: number;
        name: string;
        email: string;
    } | null;
    resolved_at?: string | null;
    responses?: TicketResponse[];
    created_at: string;
    updated_at: string;
}

export interface DashboardStats {
    total_users?: number;
    available_devices: number;
    open_tickets: number;
    my_assigned_tickets?: number;
    my_devices?: number;
    my_tickets?: number;
    my_open_tickets?: number;
}

export interface PageProps {
    auth: {
        user: User;
    };
    errors: Record<string, string>;
    flash: {
        message?: string;
        error?: string;
    };
    [key: string]: any;
}
