<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { NetworkDevice, PageProps, Ticket } from '@/types';
import {
    getPriorityBadgeClass,
    getPriorityLabel,
    getStatusBadgeClass,
    getStatusLabel,
} from '@/Utils/helpers';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Props {
    tickets: Ticket[];
    devices: NetworkDevice[];
}

const props = defineProps<Props>();

const page = usePage<PageProps>();
const user = page.props.auth.user;

const showModal = ref(false);

const form = useForm({
    title: '',
    description: '',
    priority: 'medium' as 'low' | 'medium' | 'high' | 'urgent',
    device_id: '',
});

const deleteTicket = (ticket: Ticket): void => {
    if (confirm(`¿Estás seguro de eliminar el ticket "${ticket.title}"?`)) {
        router.delete(route('tickets.destroy', ticket.id));
    }
};

const openModal = (): void => {
    showModal.value = true;
};

const closeModal = (): void => {
    showModal.value = false;
    form.reset();
};

const submit = (): void => {
    form.post(route('tickets.store'), {
        onSuccess: () => {
            closeModal();
        },
        onError: (errors) => {
            console.error('Error al crear ticket:', errors);
        },
    });
};
</script>

<template>
    <Head title="Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{
                        user.role === 'user'
                            ? 'Mis Tickets'
                            : 'Gestión de Tickets'
                    }}
                </h2>
                <button
                    @click="openModal"
                    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-indigo-700"
                >
                    <svg
                        class="mr-2 h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>
                    Crear Ticket
                </button>
            </div>
        </template>

        <FlashMessages />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Título
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Usuario
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Prioridad
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Estado
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Dispositivo
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Fecha
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        v-for="ticket in tickets"
                                        :key="ticket.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td class="px-6 py-4">
                                            <Link
                                                :href="
                                                    route(
                                                        'tickets.show',
                                                        ticket.id,
                                                    )
                                                "
                                                class="text-sm font-medium text-indigo-600 hover:text-indigo-900"
                                            >
                                                {{ ticket.title }}
                                            </Link>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ ticket.user.name }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                :class="
                                                    getPriorityBadgeClass(
                                                        ticket.priority,
                                                    )
                                                "
                                                class="inline-flex rounded-full px-2 py-1 text-xs font-semibold leading-5"
                                            >
                                                {{
                                                    getPriorityLabel(
                                                        ticket.priority,
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                :class="
                                                    getStatusBadgeClass(
                                                        ticket.status,
                                                    )
                                                "
                                                class="inline-flex rounded-full px-2 py-1 text-xs font-semibold leading-5"
                                            >
                                                {{
                                                    getStatusLabel(
                                                        ticket.status,
                                                    )
                                                }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-600"
                                        >
                                            {{
                                                ticket.device
                                                    ? ticket.device.name
                                                    : 'N/A'
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-600"
                                        >
                                            {{
                                                new Date(
                                                    ticket.created_at,
                                                ).toLocaleDateString('es-ES')
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'tickets.show',
                                                        ticket.id,
                                                    )
                                                "
                                                class="mr-3 text-indigo-600 hover:text-indigo-900"
                                                >Ver</Link
                                            >
                                            <Link
                                                v-if="
                                                    user.role === 'admin' ||
                                                    user.role === 'superadmin'
                                                "
                                                :href="
                                                    route(
                                                        'tickets.edit',
                                                        ticket.id,
                                                    )
                                                "
                                                class="mr-3 text-blue-600 hover:text-blue-900"
                                                >Editar</Link
                                            >
                                            <button
                                                v-if="
                                                    ticket.user_id ===
                                                        user.id ||
                                                    user.role === 'superadmin'
                                                "
                                                @click="deleteTicket(ticket)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div
                            v-if="tickets.length === 0"
                            class="py-12 text-center"
                        >
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"
                                />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                No hay tickets
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Comienza creando un nuevo ticket de soporte.
                            </p>
                            <div class="mt-6">
                                <Link
                                    :href="route('tickets.create')"
                                    class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
                                >
                                    <svg
                                        class="mr-2 h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        />
                                    </svg>
                                    Crear Ticket
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear ticket -->
        <Modal :show="showModal" @close="closeModal" max-width="2xl">
            <div class="p-6">
                <h2 class="mb-6 text-lg font-medium text-gray-900">
                    Crear Nuevo Ticket
                </h2>

                <form @submit.prevent="submit">
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
                        <SecondaryButton @click="closeModal">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            <span v-if="!form.processing">Crear Ticket</span>
                            <span v-else>Creando...</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
