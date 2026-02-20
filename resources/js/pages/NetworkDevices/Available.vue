<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { NetworkDevice } from '@/types';

interface Props {
    devices: NetworkDevice[];
}

defineProps<Props>();
</script>

<template>
    <Head title="Dispositivos Disponibles" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dispositivos Disponibles</h2>
                <Link
                    :href="route('devices.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition"
                >
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="devices.length === 0" class="text-center py-12">
                            <p class="text-gray-500">No hay dispositivos disponibles</p>
                        </div>

                        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div
                                v-for="device in devices"
                                :key="device.id"
                                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition"
                            >
                                <h3 class="font-semibold text-gray-900">{{ device.name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ device.type }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ device.ip_address }}</p>
                                <p class="text-xs text-gray-500 mt-2" v-if="device.location">
                                    📍 {{ device.location }}
                                </p>
                                <div class="mt-4">
                                    <Link
                                        :href="route('devices.show', device.id)"
                                        class="text-sm text-indigo-600 hover:text-indigo-900"
                                    >
                                        Ver detalles →
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
