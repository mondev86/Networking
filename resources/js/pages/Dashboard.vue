<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashMessages from '@/Components/FlashMessages.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import type { PageProps, DashboardStats, NetworkDevice, Ticket } from '@/types';
import {
    getRoleBadgeClass,
    getStatusBadgeClass,
    getPriorityBadgeClass,
    getStatusLabel,
    getPriorityLabel,
    getDeviceTypeLabel
} from '@/Utils/helpers';

interface DashboardProps {
    stats: DashboardStats;
    recentDevices: NetworkDevice[];
    recentTickets: Ticket[];
}

defineProps<DashboardProps>();

const page = usePage<PageProps>();
const user = page.props.auth.user;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <FlashMessages />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Mensaje de bienvenida -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            ¡Bienvenido, {{ user.name }}!
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">
                            <span :class="getRoleBadgeClass(user.role)" class="px-2 py-1 rounded-full text-xs font-medium">
                                {{ user.role === 'superadmin' ? 'Superadministrador' : user.role === 'admin' ? 'Administrador' : 'Usuario' }}
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Para Superadmin -->
                    <template v-if="user.role === 'superadmin'">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.total_users }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Dispositivos Disponibles</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.available_devices }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Tickets Abiertos</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.open_tickets }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Para Admin -->
                    <template v-else-if="user.role === 'admin'">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Dispositivos Disponibles</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.available_devices }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Tickets Abiertos</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.open_tickets }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Tickets Asignados</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.my_assigned_tickets }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Para Usuario -->
                    <template v-else>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Mis Dispositivos</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.my_devices }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Mis Tickets</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.my_tickets }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-600">Tickets Abiertos</p>
                                        <p class="text-2xl font-semibold text-gray-900">{{ stats.my_open_tickets }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Grid de 2 columnas para Dispositivos y Tickets -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Dispositivos Recientes -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="recentDevices.length > 0">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ user.role === 'user' ? 'Mis Dispositivos' : 'Dispositivos Recientes' }}
                                </h3>
                                <Link
                                    v-if="user.role !== 'user'"
                                    :href="route('devices.index')"
                                    class="text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    Ver todos →
                                </Link>
                            </div>
                            <div class="space-y-3">
                                <div
                                    v-for="device in recentDevices"
                                    :key="device.id"
                                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition"
                                >
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <Link
                                                :href="route('devices.show', device.id)"
                                                class="text-sm font-medium text-gray-900 hover:text-indigo-600"
                                            >
                                                {{ device.name }}
                                            </Link>
                                            <p class="text-xs text-gray-600 mt-1">
                                                {{ getDeviceTypeLabel(device.type) }} • {{ device.ip_address }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1" v-if="device.location">
                                                📍 {{ device.location }}
                                            </p>
                                        </div>
                                        <span :class="getStatusBadgeClass(device.status)" class="px-2 py-1 text-xs rounded-full font-medium">
                                            {{ getStatusLabel(device.status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tickets Recientes -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" v-if="recentTickets.length > 0">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ user.role === 'user' ? 'Mis Tickets Recientes' : 'Tickets Recientes' }}
                                </h3>
                                <Link
                                    :href="route('tickets.index')"
                                    class="text-sm text-indigo-600 hover:text-indigo-900"
                                >
                                    Ver todos →
                                </Link>
                            </div>
                            <div class="space-y-3">
                                <div
                                    v-for="ticket in recentTickets"
                                    :key="ticket.id"
                                    class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition"
                                >
                                    <div class="flex justify-between items-start mb-2">
                                        <Link
                                            :href="route('tickets.show', ticket.id)"
                                            class="text-sm font-medium text-gray-900 hover:text-indigo-600 flex-1"
                                        >
                                            {{ ticket.title }}
                                        </Link>
                                        <span :class="getPriorityBadgeClass(ticket.priority)" class="px-2 py-1 text-xs rounded-full font-medium ml-2">
                                            {{ getPriorityLabel(ticket.priority) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs text-gray-600">
                                            {{ ticket.user.name }}
                                        </p>
                                        <span :class="getStatusBadgeClass(ticket.status)" class="px-2 py-1 text-xs rounded-full font-medium">
                                            {{ getStatusLabel(ticket.status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción rápida -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link
                        :href="route('tickets.index')"
                        class="flex items-center justify-center px-4 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Nuevo Ticket
                    </Link>

                    <Link
                        v-if="user.role === 'superadmin'"
                        :href="route('devices.create')"
                        class="flex items-center justify-center px-4 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-white hover:bg-green-700 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Dispositivo
                    </Link>

                    <Link
                        v-if="user.role === 'superadmin'"
                        :href="route('users.create')"
                        class="flex items-center justify-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-white hover:bg-blue-700 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear Usuario
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
