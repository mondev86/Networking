<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import type { Ticket, PageProps, NetworkDevice } from '@/types';
import { getStatusBadgeClass, getPriorityBadgeClass, getStatusLabel, getPriorityLabel } from '@/Utils/helpers';
import Modal from '@/Components/Modal.vue';
import InputError from '@/components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
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
        }
    });
};
</script>

<template>
    <Head title="Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ user.role === 'user' ? 'Mis Tickets' : 'Gestión de Tickets' }}
                </h2>
                <button
                    @click="openModal"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crear Ticket
                </button>
            </div>
        </template>

        <FlashMessages />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispositivo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="ticket in tickets" :key="ticket.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <Link :href="route('tickets.show', ticket.id)" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                {{ ticket.title }}
                                            </Link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ ticket.user.name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getPriorityBadgeClass(ticket.priority)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ getPriorityLabel(ticket.priority) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusBadgeClass(ticket.status)" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ getStatusLabel(ticket.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ ticket.device ? ticket.device.name : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ new Date(ticket.created_at).toLocaleDateString('es-ES') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('tickets.show', ticket.id)" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</Link>
                                            <Link v-if="user.role === 'admin' || user.role === 'superadmin'" :href="route('tickets.edit', ticket.id)" class="text-blue-600 hover:text-blue-900 mr-3">Editar</Link>
                                            <button v-if="ticket.user_id === user.id || user.role === 'superadmin'" @click="deleteTicket(ticket)" class="text-red-600 hover:text-red-900">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div v-if="tickets.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tickets</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo ticket de soporte.</p>
                            <div class="mt-6">
                                <Link :href="route('tickets.create')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
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
                <h2 class="text-lg font-medium text-gray-900 mb-6">Crear Nuevo Ticket</h2>

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
