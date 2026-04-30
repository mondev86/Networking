<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

interface NetworkDevice {
    id: string | number;
    name: string;
    type: string;
}

interface Props {
    devices: NetworkDevice[];
}

const props = defineProps<Props>();

const form = useForm({
    title: '',
    description: '',
    priority: 'medium' as 'low' | 'medium' | 'high' | 'urgent',
    device_id: '',
});

const submit = (): void => {
    form.post(route('tickets.store'), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error('Error al crear ticket:', errors);
        },
    });
};

const handleCancel = (): void => {
    router.get(route('tickets.index'));
};
</script>

<template>
    <Head title="Crear Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Crear Nuevo Ticket
                </h2>
                <Link
                    :href="route('tickets.index')"
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
                            <!-- Título -->
                            <div>
                                <InputLabel
                                    for="title"
                                    value="Título del ticket"
                                />
                                <TextInput
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    placeholder="Ej: Problema con conexión a internet"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.title"
                                />
                            </div>

                            <!-- Descripción -->
                            <div>
                                <InputLabel
                                    for="description"
                                    value="Descripción del problema"
                                />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="6"
                                    required
                                    placeholder="Describe el problema con el mayor detalle posible..."
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.description"
                                />
                            </div>

                            <!-- Prioridad -->
                            <div>
                                <InputLabel for="priority" value="Prioridad" />
                                <select
                                    id="priority"
                                    v-model="form.priority"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="low">
                                        🟢 Baja - No urgente
                                    </option>
                                    <option value="medium">
                                        🟡 Media - Normal
                                    </option>
                                    <option value="high">
                                        🟠 Alta - Requiere atención pronto
                                    </option>
                                    <option value="urgent">
                                        🔴 Urgente - Crítico
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.priority"
                                />
                            </div>

                            <!-- Dispositivo -->
                            <div>
                                <InputLabel
                                    for="device_id"
                                    value="Dispositivo relacionado (opcional)"
                                />
                                <select
                                    id="device_id"
                                    v-model="form.device_id"
                                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Ninguno</option>
                                    <option
                                        v-for="device in props.devices"
                                        :key="device.id"
                                        :value="device.id"
                                    >
                                        {{ device.name }} - {{ device.type }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.device_id"
                                />
                                <p class="mt-1 text-sm text-gray-600">
                                    Si el problema está relacionado con un
                                    dispositivo específico, selecciónalo aquí.
                                </p>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-6 flex items-center justify-end gap-4">
                            <button
                                type="button"
                                @click="handleCancel"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition hover:bg-gray-400"
                            >
                                Cancelar
                            </button>
                            <PrimaryButton :disabled="form.processing">
                                <span v-if="!form.processing"
                                    >Crear Ticket</span
                                >
                                <span v-else>Creando...</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
