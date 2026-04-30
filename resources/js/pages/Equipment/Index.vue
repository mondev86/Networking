<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">
                    Equipos Informáticos
                </h2>
                <Link
                    :href="route('equipment.create')"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                >
                    ➕ Nuevo Equipo
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <!-- Tabla de Dispositivos -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead
                                    class="bg-gray-50 font-semibold text-gray-700"
                                >
                                    <tr>
                                        <th class="px-6 py-3">Serie</th>
                                        <th class="px-6 py-3">Modelo</th>
                                        <th class="px-6 py-3">Tipo</th>
                                        <th class="px-6 py-3">Estado</th>
                                        <th class="px-6 py-3">Asignado a</th>
                                        <th class="px-6 py-3">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr
                                        v-for="device in devices.data"
                                        :key="device.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-6 py-4 font-mono text-xs text-gray-600"
                                        >
                                            {{ device.serial_number }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ device.model }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-block rounded-full px-3 py-1 text-xs font-semibold"
                                                :class="
                                                    getTypeClass(device.type)
                                                "
                                            >
                                                {{ device.type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-block rounded-full px-3 py-1 text-xs font-semibold"
                                                :class="
                                                    getStatusClass(
                                                        device.status,
                                                    )
                                                "
                                            >
                                                {{ device.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                v-if="device.current_assignment"
                                                class="text-sm text-gray-700"
                                            >
                                                {{
                                                    device.current_assignment
                                                        ?.assignable?.name ||
                                                    'N/A'
                                                }}
                                            </span>
                                            <span
                                                v-else
                                                class="text-sm text-gray-400"
                                                >Sin asignar</span
                                            >
                                        </td>
                                        <td class="flex gap-2 px-6 py-4">
                                            <Link
                                                :href="
                                                    route(
                                                        'equipment.show',
                                                        device.id,
                                                    )
                                                "
                                                class="font-semibold text-blue-600 hover:text-blue-800"
                                            >
                                                Ver
                                            </Link>
                                            <Link
                                                :href="
                                                    route(
                                                        'equipment.edit',
                                                        device.id,
                                                    )
                                                "
                                                class="font-semibold text-yellow-600 hover:text-yellow-800"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                @click="deleteDevice(device.id)"
                                                class="font-semibold text-red-600 hover:text-red-800"
                                            >
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div
                            v-if="devices.links"
                            class="mt-6 flex justify-center gap-2"
                        >
                            <Link
                                v-for="link in devices.links"
                                :key="link.url + link.label"
                                :href="link.url || '#'"
                                :class="{
                                    'rounded border bg-blue-600 px-3 py-1 text-white':
                                        link.active,
                                    'rounded border border-gray-300 bg-white px-3 py-1 text-gray-700':
                                        !link.active,
                                    'cursor-not-allowed opacity-50': !link.url,
                                }"
                                :disabled="!link.url"
                            >
                                <span v-html="link.label"></span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';

interface Device {
    id: number;
    serial_number: string;
    model: string;
    type: string;
    status: string;
    current_assignment?: {
        assignable?: {
            name: string;
        };
    };
}

interface Props {
    devices: {
        data: Device[];
        links: any[];
    };
}

defineProps<Props>();

const getTypeClass = (type: string): string => {
    const classes: Record<string, string> = {
        laptop: 'bg-blue-100 text-blue-800',
        monitor: 'bg-purple-100 text-purple-800',
        printer: 'bg-orange-100 text-orange-800',
        keyboard: 'bg-green-100 text-green-800',
        mouse: 'bg-pink-100 text-pink-800',
        other: 'bg-gray-100 text-gray-800',
    };
    return classes[type] || 'bg-gray-100 text-gray-800';
};

const getStatusClass = (status: string): string => {
    const classes: Record<string, string> = {
        available: 'bg-green-100 text-green-800',
        in_use: 'bg-blue-100 text-blue-800',
        broken: 'bg-red-100 text-red-800',
        retired: 'bg-gray-100 text-gray-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const deleteDevice = (deviceId: number): void => {
    if (confirm('¿Estás seguro de que quieres eliminar este dispositivo?')) {
        router.delete(route('equipment.destroy', deviceId));
    }
};
</script>
