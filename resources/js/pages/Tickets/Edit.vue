<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { Ticket, User } from '@/types';

interface Props {
    ticket: Ticket;
    admins: User[];
}

const props = defineProps<Props>();

const form = useForm({
    status: props.ticket.status,
    priority: props.ticket.priority,
    assigned_to: props.ticket.assigned_to?.toString() || '',
});

const submit = (): void => {
    form.put(route('tickets.update', props.ticket.id));
};
</script>

<template>
    <Head :title="`Gestionar Ticket: ${ticket.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestionar Ticket #{{ ticket.id }}</h2>
                <Link :href="route('tickets.show', ticket.id)" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <!-- Información del Ticket -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">{{ ticket.title }}</h3>
                        <p class="text-sm text-gray-600 mb-4">Por: {{ ticket.user.name }}</p>
                        <p class="text-gray-700 whitespace-pre-wrap border-t pt-4">{{ ticket.description }}</p>
                    </div>
                </div>

                <!-- Formulario de Gestión -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="space-y-6">
                            <div>
                                <InputLabel for="status" value="Estado del ticket" />
                                <select id="status" v-model="form.status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="open">🟡 Abierto</option>
                                    <option value="in_progress">🔵 En progreso</option>
                                    <option value="resolved">🟢 Resuelto</option>
                                    <option value="closed">⚫ Cerrado</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                                <p class="mt-1 text-sm text-gray-600">Actualiza el estado según el progreso del ticket.</p>
                            </div>

                            <div>
                                <InputLabel for="priority" value="Prioridad" />
                                <select id="priority" v-model="form.priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="low">🟢 Baja</option>
                                    <option value="medium">🟡 Media</option>
                                    <option value="high">🟠 Alta</option>
                                    <option value="critical">🔴 Crítica</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.priority" />
                            </div>

                            <div>
                                <InputLabel for="assigned_to" value="Asignar a administrador" />
                                <select id="assigned_to" v-model="form.assigned_to" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Sin asignar</option>
                                    <option v-for="admin in admins" :key="admin.id" :value="admin.id">{{ admin.name }} ({{ admin.role === 'superadmin' ? 'Superadmin' : 'Admin' }})</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.assigned_to" />
                                <p class="mt-1 text-sm text-gray-600">Asigna este ticket a un administrador específico.</p>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-800"><strong>Nota:</strong> Los cambios se guardarán automáticamente. Puedes agregar comentarios y respuestas desde la vista del ticket.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <Link :href="route('tickets.show', ticket.id)" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancelar
                            </Link>
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Actualizar Ticket
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
