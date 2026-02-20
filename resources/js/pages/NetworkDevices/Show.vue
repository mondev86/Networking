<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { NetworkDevice } from '@/types';

interface Props {
    device: NetworkDevice;
}

const { device } = defineProps<Props>();

const form = useForm({});

const deleteDevice = () => {
    if (confirm('¿Estás seguro de que deseas eliminar este dispositivo?')) {
        form.delete(route('devices.destroy', device.id));
    }
};
</script>

<template>
    <Head :title="`Dispositivo: ${device.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ device.name }}</h2>
                <div class="flex gap-2">
                    <Link
                        :href="route('devices.edit', device.id)"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition"
                    >
                        Editar
                    </Link>
                    <Link
                        :href="route('devices.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition"
                    >
                        ← Volver
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Información General -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Nombre</dt>
                                        <dd class="text-sm text-gray-900">{{ device.name }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Tipo</dt>
                                        <dd class="text-sm text-gray-900 capitalize">{{ device.type }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Estado</dt>
                                        <dd class="text-sm">
                                            <span
                                                class="px-2 py-1 text-xs rounded-full font-medium"
                                                :class="{
                                                    'bg-green-100 text-green-800': device.status === 'available',
                                                    'bg-blue-100 text-blue-800': device.status === 'assigned',
                                                    'bg-yellow-100 text-yellow-800': device.status === 'maintenance',
                                                    'bg-red-100 text-red-800': device.status === 'offline',
                                                }"
                                            >
                                                {{ device.status }}
                                            </span>
                                        </dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Información Técnica -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Técnica</h3>
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-600">Dirección IP</dt>
                                        <dd class="text-sm text-gray-900 font-mono">{{ device.ip_address }}</dd>
                                    </div>
                                    <div v-if="device.mac_address">
                                        <dt class="text-sm font-medium text-gray-600">Dirección MAC</dt>
                                        <dd class="text-sm text-gray-900 font-mono">{{ device.mac_address }}</dd>
                                    </div>
                                    <div v-if="device.location">
                                        <dt class="text-sm font-medium text-gray-600">Ubicación</dt>
                                        <dd class="text-sm text-gray-900">📍 {{ device.location }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div v-if="device.notes" class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Notas</h3>
                            <p class="text-sm text-gray-600">{{ device.notes }}</p>
                        </div>

                        <!-- Fechas -->
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Registro</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <dt class="font-medium text-gray-600">Creado</dt>
                                    <dd class="text-gray-900">{{ new Date(device.created_at).toLocaleDateString('es-ES') }}</dd>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <dt class="font-medium text-gray-600">Actualizado</dt>
                                    <dd class="text-gray-900">{{ new Date(device.updated_at).toLocaleDateString('es-ES') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Botón Eliminar -->
                        <div class="mt-6 pt-6 border-t">
                            <button
                                @click="deleteDevice"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition"
                            >
                                Eliminar Dispositivo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
