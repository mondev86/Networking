<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
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
        }
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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Nuevo Ticket</h2>
                <Link
                    :href="route('tickets.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition"
                >
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="space-y-6">
                            <!-- Título -->
                            <div>
                                <InputLabel for="title" value="Título del ticket" />
                                <TextInput
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                    placeholder="Ej: Problema con conexión a internet"
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <!-- Descripción -->
                            <div>
                                <InputLabel for="description" value="Descripción del problema" />
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                                    rows="6"
                                    required
                                    placeholder="Describe el problema con el mayor detalle posible..."
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <!-- Prioridad -->
                            <div>
                                <InputLabel for="priority" value="Prioridad" />
                                <select
                                    id="priority"
                                    v-model="form.priority"
                                    class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                                    required
                                >
                                    <option value="low">🟢 Baja - No urgente</option>
                                    <option value="medium">🟡 Media - Normal</option>
                                    <option value="high">🟠 Alta - Requiere atención pronto</option>
                                    <option value="urgent">🔴 Urgente - Crítico</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.priority" />
                            </div>

                            <!-- Dispositivo -->
                            <div>
                                <InputLabel for="device_id" value="Dispositivo relacionado (opcional)" />
                                <select
                                    id="device_id"
                                    v-model="form.device_id"
                                    class="mt-1 block w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm px-3 py-2"
                                >
                                    <option value="">Ninguno</option>
                                    <option v-for="device in props.devices" :key="device.id" :value="device.id">
                                        {{ device.name }} - {{ device.type }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.device_id" />
                                <p class="mt-1 text-sm text-gray-600">Si el problema está relacionado con un dispositivo específico, selecciónalo aquí.</p>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end mt-6 gap-4">
                            <button
                                type="button"
                                @click="handleCancel"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 transition"
                            >
                                Cancelar
                            </button>
                            <PrimaryButton :disabled="form.processing">
                                <span v-if="!form.processing">Crear Ticket</span>
                                <span v-else>Creando...</span>
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
