<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Ticket, User } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Gestionar Ticket #{{ ticket.id }}
                </h2>
                <Link
                    :href="route('tickets.show', ticket.id)"
                    class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700"
                >
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <!-- Información del Ticket -->
                <div
                    class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <h3 class="mb-2 text-lg font-semibold">
                            {{ ticket.title }}
                        </h3>
                        <p class="mb-4 text-sm text-gray-600">
                            Por: {{ ticket.user.name }}
                        </p>
                        <p
                            class="whitespace-pre-wrap border-t pt-4 text-gray-700"
                        >
                            {{ ticket.description }}
                        </p>
                    </div>
                </div>

                <!-- Formulario de Gestión -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="space-y-6">
                            <div>
                                <InputLabel
                                    for="status"
                                    value="Estado del ticket"
                                />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="open">🟡 Abierto</option>
                                    <option value="in_progress">
                                        🔵 En progreso
                                    </option>
                                    <option value="resolved">
                                        🟢 Resuelto
                                    </option>
                                    <option value="closed">⚫ Cerrado</option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.status"
                                />
                                <p class="mt-1 text-sm text-gray-600">
                                    Actualiza el estado según el progreso del
                                    ticket.
                                </p>
                            </div>

                            <div>
                                <InputLabel for="priority" value="Prioridad" />
                                <select
                                    id="priority"
                                    v-model="form.priority"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="low">🟢 Baja</option>
                                    <option value="medium">🟡 Media</option>
                                    <option value="high">🟠 Alta</option>
                                    <option value="critical">🔴 Crítica</option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.priority"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="assigned_to"
                                    value="Asignar a administrador"
                                />
                                <select
                                    id="assigned_to"
                                    v-model="form.assigned_to"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option value="">Sin asignar</option>
                                    <option
                                        v-for="admin in admins"
                                        :key="admin.id"
                                        :value="admin.id"
                                    >
                                        {{ admin.name }} ({{
                                            admin.role === 'superadmin'
                                                ? 'Superadmin'
                                                : 'Admin'
                                        }})
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.assigned_to"
                                />
                                <p class="mt-1 text-sm text-gray-600">
                                    Asigna este ticket a un administrador
                                    específico.
                                </p>
                            </div>

                            <div
                                class="rounded-lg border border-blue-200 bg-blue-50 p-4"
                            >
                                <p class="text-sm text-blue-800">
                                    <strong>Nota:</strong> Los cambios se
                                    guardarán automáticamente. Puedes agregar
                                    comentarios y respuestas desde la vista del
                                    ticket.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <Link
                                :href="route('tickets.show', ticket.id)"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-400"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Actualizar Ticket
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
