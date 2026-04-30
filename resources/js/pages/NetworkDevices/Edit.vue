<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { NetworkDevice } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Props {
    device: NetworkDevice;
    users: any[];
}

const props = defineProps<Props>();

const form = useForm({
    name: props.device.name,
    type: props.device.type,
    ip_address: props.device.ip_address,
    mac_address: props.device.mac_address || '',
    location: props.device.location || '',
    status: props.device.status,
    notes: props.device.notes || '',
});

const submit = (): void => {
    form.put(route('devices.update', props.device.id));
};
</script>

<template>
    <Head :title="`Editar: ${device.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Editar Dispositivo
                </h2>
                <Link
                    :href="route('devices.show', device.id)"
                    class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-gray-700"
                >
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="space-y-6">
                            <!-- Nombre -->
                            <div>
                                <InputLabel
                                    for="name"
                                    value="Nombre del dispositivo"
                                />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <!-- Tipo -->
                            <div>
                                <InputLabel
                                    for="type"
                                    value="Tipo de dispositivo"
                                />
                                <select
                                    id="type"
                                    v-model="form.type"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="router">Router</option>
                                    <option value="switch">Switch</option>
                                    <option value="firewall">Firewall</option>
                                    <option value="server">Servidor</option>
                                    <option value="printer">Impresora</option>
                                    <option value="camera">Cámara</option>
                                    <option value="other">Otro</option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.type"
                                />
                            </div>

                            <!-- IP Address -->
                            <div>
                                <InputLabel
                                    for="ip_address"
                                    value="Dirección IP"
                                />
                                <TextInput
                                    id="ip_address"
                                    v-model="form.ip_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.ip_address"
                                />
                            </div>

                            <!-- MAC Address -->
                            <div>
                                <InputLabel
                                    for="mac_address"
                                    value="Dirección MAC (opcional)"
                                />
                                <TextInput
                                    id="mac_address"
                                    v-model="form.mac_address"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.mac_address"
                                />
                            </div>

                            <!-- Ubicación -->
                            <div>
                                <InputLabel
                                    for="location"
                                    value="Ubicación (opcional)"
                                />
                                <TextInput
                                    id="location"
                                    v-model="form.location"
                                    type="text"
                                    class="mt-1 block w-full"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.location"
                                />
                            </div>

                            <!-- Estado -->
                            <div>
                                <InputLabel for="status" value="Estado" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="online">🟢 En línea</option>
                                    <option value="offline">
                                        🔴 Fuera de línea
                                    </option>
                                    <option value="maintenance">
                                        🟡 Mantenimiento
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.status"
                                />
                            </div>

                            <!-- Notas -->
                            <div>
                                <InputLabel
                                    for="notes"
                                    value="Notas (opcional)"
                                />
                                <textarea
                                    id="notes"
                                    v-model="form.notes"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="4"
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.notes"
                                />
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-6 flex items-center justify-end gap-4">
                            <Link
                                :href="route('devices.show', device.id)"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-400"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton :disabled="form.processing">
                                <span v-if="!form.processing"
                                    >Guardar Cambios</span
                                >
                                <span v-else>Guardando...</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
