<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { NetworkDevice } from '@/types';
import { Head, Link } from '@inertiajs/vue3';

interface Props {
    devices: NetworkDevice[];
}

defineProps<Props>();
</script>

<template>
    <Head title="Dispositivos Disponibles" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dispositivos Disponibles
                </h2>
                <Link
                    :href="route('devices.index')"
                    class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700"
                >
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div
                            v-if="devices.length === 0"
                            class="py-12 text-center"
                        >
                            <p class="text-gray-500">
                                No hay dispositivos disponibles
                            </p>
                        </div>

                        <div
                            v-else
                            class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3"
                        >
                            <div
                                v-for="device in devices"
                                :key="device.id"
                                class="rounded-lg border border-gray-200 p-4 transition hover:shadow-md"
                            >
                                <h3 class="font-semibold text-gray-900">
                                    {{ device.name }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ device.type }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600">
                                    {{ device.ip_address }}
                                </p>
                                <p
                                    class="mt-2 text-xs text-gray-500"
                                    v-if="device.location"
                                >
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
