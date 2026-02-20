export const getRoleBadgeClass = (role: string): string => {
    const classes: Record<string, string> = {
        superadmin: 'bg-purple-100 text-purple-800',
        admin: 'bg-blue-100 text-blue-800',
        user: 'bg-gray-100 text-gray-800',
    };
    return classes[role] || classes.user;
};

export const getRoleLabel = (role: string): string => {
    const labels: Record<string, string> = {
        superadmin: 'Superadministrador',
        admin: 'Administrador',
        user: 'Usuario',
    };
    return labels[role] || role;
};

export const getStatusBadgeClass = (status: string): string => {
    const classes: Record<string, string> = {
        available: 'bg-green-100 text-green-800',
        assigned: 'bg-blue-100 text-blue-800',
        maintenance: 'bg-yellow-100 text-yellow-800',
        offline: 'bg-red-100 text-red-800',
        open: 'bg-yellow-100 text-yellow-800',
        in_progress: 'bg-blue-100 text-blue-800',
        resolved: 'bg-green-100 text-green-800',
        closed: 'bg-gray-100 text-gray-800',
    };
    return classes[status] || classes.open;
};

export const getStatusLabel = (status: string): string => {
    const labels: Record<string, string> = {
        available: 'Disponible',
        assigned: 'Asignado',
        maintenance: 'Mantenimiento',
        offline: 'Fuera de línea',
        open: 'Abierto',
        in_progress: 'En progreso',
        resolved: 'Resuelto',
        closed: 'Cerrado',
    };
    return labels[status] || status;
};

export const getPriorityBadgeClass = (priority: string): string => {
    const classes: Record<string, string> = {
        low: 'bg-gray-100 text-gray-800',
        medium: 'bg-yellow-100 text-yellow-800',
        high: 'bg-orange-100 text-orange-800',
        critical: 'bg-red-100 text-red-800',
    };
    return classes[priority] || classes.medium;
};

export const getPriorityLabel = (priority: string): string => {
    const labels: Record<string, string> = {
        low: 'Baja',
        medium: 'Media',
        high: 'Alta',
        critical: 'Crítica',
    };
    return labels[priority] || priority;
};

export const getDeviceTypeLabel = (type: string): string => {
    const labels: Record<string, string> = {
        router: 'Router',
        switch: 'Switch',
        firewall: 'Firewall',
        access_point: 'Access Point',
        server: 'Servidor',
    };
    return labels[type] || type;
};
