<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { User } from '@/types';

interface Props {
    users: User[];
}

defineProps<Props>();

const form = useForm({
    name: '',
    type: 'router' as 'router' | 'switch' | 'firewall' | 'access_point' | 'server',
    ip_address: '',
    mac_address: '',
    location: '',
    status: 'available' as 'available' | 'assigned' | 'maintenance' | 'offline',
    assigned_to: '',
    purchase_date: '',
    warranty_expiration: '',
    notes: '',
});

const submit = (): void => {
    form.post(route('devices.store'));
};
</script>

<template>
    <Head title="Agregar Dispositivo" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agregar Nuevo Dispositivo</h2>
                <Link :href="route('devices.index')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <InputLabel for="name" value="Nombre del dispositivo *" />
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus placeholder="Ej: Router Principal" />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="type" value="Tipo de dispositivo *" />
                                <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="router">Router</option>
                                    <option value="switch">Switch</option>
                                    <option value="firewall">Firewall</option>
                                    <option value="access_point">Access Point</option>
                                    <option value="server">Servidor</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>

                            <div>
                                <InputLabel for="status" value="Estado *" />
                                <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="available">Disponible</option>
                                    <option value="assigned">Asignado</option>
                                    <option value="maintenance">Mantenimiento</option>
                                    <option value="offline">Fuera de línea</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div>
                                <InputLabel for="ip_address" value="Dirección IP" />
                                <TextInput id="ip_address" v-model="form.ip_address" type="text" class="mt-1 block w-full" placeholder="192.168.1.1" />
                                <InputError class="mt-2" :message="form.errors.ip_address" />
                            </div>

                            <div>
                                <InputLabel for="mac_address" value="Dirección MAC" />
                                <TextInput id="mac_address" v-model="form.mac_address" type="text" class="mt-1 block w-full" placeholder="00:11:22:33:44:55" />
                                <InputError class="mt-2" :message="form.errors.mac_address" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="location" value="Ubicación física" />
                                <TextInput id="location" v-model="form.location" type="text" class="mt-1 block w-full" placeholder="Ej: Sala de servidores - Rack A" />
                                <InputError class="mt-2" :message="form.errors.location" />
                            </div>

                            <div>
                                <InputLabel for="assigned_to" value="Asignar a usuario" />
                                <select id="assigned_to" v-model="form.assigned_to" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Sin asignar</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.assigned_to" />
                            </div>

                            <div>
                                <InputLabel for="purchase_date" value="Fecha de compra" />
                                <TextInput id="purchase_date" v-model="form.purchase_date" type="date" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.purchase_date" />
                            </div>

                            <div>
                                <InputLabel for="warranty_expiration" value="Expiración de garantía" />
                                <TextInput id="warranty_expiration" v-model="form.warranty_expiration" type="date" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.warranty_expiration" />
                            </div>

                            <div class="md:col-span-2">
                                <InputLabel for="notes" value="Notas adicionales" />
                                <textarea id="notes" v-model="form.notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="Información adicional sobre el dispositivo..."></textarea>
                                <InputError class="mt-2" :message="form.errors.notes" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <Link :href="route('devices.index')" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancelar
                            </Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Crear Dispositivo
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
