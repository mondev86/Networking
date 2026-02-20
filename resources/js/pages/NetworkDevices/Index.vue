<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { NetworkDevice } from '@/types';

interface Props {
    devices: NetworkDevice[];
}

defineProps<Props>();

const showCreateModal = ref(false);

const createForm = useForm({
    name: '',
    type: 'router',
    ip_address: '',
    mac_address: '',
    location: '',
    status: 'online',
});

const openCreateModal = () => {
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
    createForm.reset();
};

const submitCreateForm = () => {
    createForm.post(route('devices.store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeCreateModal();
        },
    });
};
</script>

<template>
    <Head title="Dispositivos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dispositivos de Red</h2>
            <button
                @click="openCreateModal"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition"
            >
                + Crear Dispositivo
            </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="devices.length === 0" class="text-center py-12">
                            <p class="text-gray-500">No hay dispositivos registrados</p>
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-100 border-b">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Tipo</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">IP Address</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Ubicación</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Estado</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <tr v-for="device in devices" :key="device.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-900">{{ device.name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ device.type }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ device.ip_address }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ device.location || '-' }}</td>
                                        <td class="px-6 py-4 text-sm">
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
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <Link
                                                :href="route('devices.show', device.id)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3"
                                            >
                                                Ver
                                            </Link>
                                            <Link
                                                :href="route('devices.edit', device.id)"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Editar
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- Create Device Modal -->
    <Modal :show="showCreateModal" @close="closeCreateModal" max-width="2xl">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">Crear Nuevo Dispositivo</h2>

            <form @submit.prevent="submitCreateForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <InputLabel for="name" value="Nombre" />
                        <TextInput
                            id="name"
                            v-model="createForm.name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError class="mt-2" :message="createForm.errors.name" />
                    </div>

                    <!-- Type -->
                    <div>
                        <InputLabel for="type" value="Tipo" />
                        <select
                            id="type"
                            v-model="createForm.type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="router">Router</option>
                            <option value="switch">Switch</option>
                            <option value="firewall">Firewall</option>
                            <option value="access_point">Access Point</option>
                            <option value="server">Server</option>
                        </select>
                        <InputError class="mt-2" :message="createForm.errors.type" />
                    </div>

                    <!-- IP Address -->
                    <div>
                        <InputLabel for="ip_address" value="Dirección IP" />
                        <TextInput
                            id="ip_address"
                            v-model="createForm.ip_address"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="192.168.1.1"
                            required
                        />
                        <InputError class="mt-2" :message="createForm.errors.ip_address" />
                    </div>

                    <!-- MAC Address -->
                    <div>
                        <InputLabel for="mac_address" value="Dirección MAC" />
                        <TextInput
                            id="mac_address"
                            v-model="createForm.mac_address"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="00:11:22:33:44:55"
                        />
                        <InputError class="mt-2" :message="createForm.errors.mac_address" />
                    </div>

                    <!-- Location -->
                    <div>
                        <InputLabel for="location" value="Ubicación" />
                        <TextInput
                            id="location"
                            v-model="createForm.location"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Oficina principal, piso 2"
                        />
                        <InputError class="mt-2" :message="createForm.errors.location" />
                    </div>

                    <!-- Status -->
                    <div>
                        <InputLabel for="status" value="Estado" />
                        <select
                            id="status"
                            v-model="createForm.status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="online">🟢 En línea</option>
                            <option value="offline">🔴 Fuera de línea</option>
                            <option value="maintenance">🟡 Mantenimiento</option>
                        </select>
                        <InputError class="mt-2" :message="createForm.errors.status" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-6 border-t">
                    <SecondaryButton @click="closeCreateModal">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': createForm.processing }" :disabled="createForm.processing">
                        Crear Dispositivo
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
