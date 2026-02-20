<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue'
import type { Ticket, PageProps } from '@/types';
import { getStatusBadgeClass, getPriorityBadgeClass, getStatusLabel, getPriorityLabel } from '@/Utils/helpers';

interface Props {
    ticket: Ticket;
}

const props = defineProps<Props>();

const page = usePage<PageProps>();
const user = page.props.auth.user;

const showResponseForm = ref(false);

const responseForm = useForm({
    message: '',
    is_internal: false,
});

const submitResponse = (): void => {
    responseForm.post(route('tickets.responses.store', props.ticket.id), {
        preserveScroll: true,
        onSuccess: () => {
            responseForm.reset();
            showResponseForm.value = false;
        },
    });
};

const deleteResponse = (responseId: number): void => {
    if (confirm('¿Estás seguro de eliminar esta respuesta?')) {
        router.delete(route('tickets.responses.destroy', responseId), {
            preserveScroll: true,
        });
    }
};

const canRespondTickets = user.role === 'admin' || user.role === 'superadmin';
</script>

<template>
    <Head :title="`Ticket: ${ticket.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ticket #{{ ticket.id }}</h2>
                <div class="flex gap-2">
                    <Link v-if="canRespondTickets" :href="route('tickets.edit', ticket.id)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Gestionar
                    </Link>
                    <Link :href="route('tickets.index')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        ← Volver
                    </Link>
                </div>
            </div>
        </template>

        <FlashMessages />

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Información del Ticket -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-2xl font-bold text-gray-900">{{ ticket.title }}</h3>
                            <div class="flex gap-2">
                                <span :class="getPriorityBadgeClass(ticket.priority)" class="px-3 py-1 text-sm rounded-full font-medium">
                                    {{ getPriorityLabel(ticket.priority) }}
                                </span>
                                <span :class="getStatusBadgeClass(ticket.status)" class="px-3 py-1 text-sm rounded-full font-medium">
                                    {{ getStatusLabel(ticket.status) }}
                                </span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ ticket.description }}</p>
                        </div>

                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-200">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Creado por</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ticket.user.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de creación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ new Date(ticket.created_at).toLocaleString('es-ES') }}</dd>
                            </div>
                            <div v-if="ticket.device">
                                <dt class="text-sm font-medium text-gray-500">Dispositivo relacionado</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <Link :href="route('devices.show', ticket.device.id)" class="text-indigo-600 hover:text-indigo-900">
                                        {{ ticket.device.name }}
                                    </Link>
                                </dd>
                            </div>
                            <div v-if="ticket.assigned_admin">
                                <dt class="text-sm font-medium text-gray-500">Asignado a</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ticket.assigned_admin.name }}</dd>
                            </div>
                            <div v-if="ticket.resolved_at">
                                <dt class="text-sm font-medium text-gray-500">Fecha de resolución</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ new Date(ticket.resolved_at).toLocaleString('es-ES') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Respuestas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Respuestas ({{ ticket.responses?.length || 0 }})</h3>

                        <div class="space-y-4" v-if="ticket.responses && ticket.responses.length > 0">
                            <div v-for="response in ticket.responses" :key="response.id" :class="['border rounded-lg p-4', response.is_internal ? 'bg-yellow-50 border-yellow-200' : 'bg-white border-gray-200']">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ response.user.name }}</span>
                                        <span v-if="response.is_internal" class="ml-2 px-2 py-1 text-xs bg-yellow-200 text-yellow-800 rounded">Nota Interna</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">{{ new Date(response.created_at).toLocaleString('es-ES') }}</span>
                                        <button v-if="response.user_id === user.id || user.role === 'superadmin'" @click="deleteResponse(response.id)" class="text-red-600 hover:text-red-900 text-sm">Eliminar</button>
                                    </div>
                                </div>
                                <p class="text-gray-700 whitespace-pre-wrap">{{ response.message }}</p>
                            </div>
                        </div>

                        <div v-else class="text-center py-8 text-gray-500">
                            <p>Aún no hay respuestas en este ticket.</p>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Respuesta -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <button v-if="!showResponseForm" @click="showResponseForm = true" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition">
                            + Agregar Respuesta
                        </button>

                        <form v-if="showResponseForm" @submit.prevent="submitResponse" class="space-y-4">
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Tu respuesta</label>
                                <textarea id="message" v-model="responseForm.message" rows="4" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Escribe tu respuesta aquí..." required></textarea>
                                <InputError class="mt-2" :message="responseForm.errors.message" />
                            </div>

                            <div v-if="canRespondTickets" class="flex items-center">
                                <input id="is_internal" v-model="responseForm.is_internal" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" />
                                <label for="is_internal" class="ml-2 block text-sm text-gray-700">Marcar como nota interna (solo visible para administradores)</label>
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <button type="button" @click="showResponseForm = false; responseForm.reset();" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-md transition">Cancelar</button>
                                <PrimaryButton :class="{ 'opacity-25': responseForm.processing }" :disabled="responseForm.processing">Enviar Respuesta</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
