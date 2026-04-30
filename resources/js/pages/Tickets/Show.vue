<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { PageProps, Ticket } from '@/types';
import {
    getPriorityBadgeClass,
    getPriorityLabel,
    getStatusBadgeClass,
    getStatusLabel,
} from '@/Utils/helpers';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Ticket #{{ ticket.id }}
                </h2>
                <div class="flex gap-2">
                    <Link
                        v-if="canRespondTickets"
                        :href="route('tickets.edit', ticket.id)"
                        class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-blue-700"
                    >
                        Gestionar
                    </Link>
                    <Link
                        :href="route('tickets.index')"
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700"
                    >
                        ← Volver
                    </Link>
                </div>
            </div>
        </template>

        <FlashMessages />

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <!-- Información del Ticket -->
                <div
                    class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <div class="mb-4 flex items-start justify-between">
                            <h3 class="text-2xl font-bold text-gray-900">
                                {{ ticket.title }}
                            </h3>
                            <div class="flex gap-2">
                                <span
                                    :class="
                                        getPriorityBadgeClass(ticket.priority)
                                    "
                                    class="rounded-full px-3 py-1 text-sm font-medium"
                                >
                                    {{ getPriorityLabel(ticket.priority) }}
                                </span>
                                <span
                                    :class="getStatusBadgeClass(ticket.status)"
                                    class="rounded-full px-3 py-1 text-sm font-medium"
                                >
                                    {{ getStatusLabel(ticket.status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 border-t border-gray-200 pt-4">
                            <p class="whitespace-pre-wrap text-gray-700">
                                {{ ticket.description }}
                            </p>
                        </div>

                        <dl
                            class="mt-6 grid grid-cols-1 gap-4 border-t border-gray-200 pt-6 md:grid-cols-2"
                        >
                            <div>
                                <dt class="text-sm font-medium text-gray-500">
                                    Creado por
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ ticket.user.name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">
                                    Fecha de creación
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{
                                        new Date(
                                            ticket.created_at,
                                        ).toLocaleString('es-ES')
                                    }}
                                </dd>
                            </div>
                            <div v-if="ticket.device">
                                <dt class="text-sm font-medium text-gray-500">
                                    Dispositivo relacionado
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <Link
                                        :href="
                                            route(
                                                'devices.show',
                                                ticket.device.id,
                                            )
                                        "
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        {{ ticket.device.name }}
                                    </Link>
                                </dd>
                            </div>
                            <div v-if="ticket.assigned_admin">
                                <dt class="text-sm font-medium text-gray-500">
                                    Asignado a
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ ticket.assigned_admin.name }}
                                </dd>
                            </div>
                            <div v-if="ticket.resolved_at">
                                <dt class="text-sm font-medium text-gray-500">
                                    Fecha de resolución
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{
                                        new Date(
                                            ticket.resolved_at,
                                        ).toLocaleString('es-ES')
                                    }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Respuestas -->
                <div
                    class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            Respuestas ({{ ticket.responses?.length || 0 }})
                        </h3>

                        <div
                            class="space-y-4"
                            v-if="
                                ticket.responses && ticket.responses.length > 0
                            "
                        >
                            <div
                                v-for="response in ticket.responses"
                                :key="response.id"
                                :class="[
                                    'rounded-lg border p-4',
                                    response.is_internal
                                        ? 'border-yellow-200 bg-yellow-50'
                                        : 'border-gray-200 bg-white',
                                ]"
                            >
                                <div
                                    class="mb-2 flex items-start justify-between"
                                >
                                    <div>
                                        <span
                                            class="font-medium text-gray-900"
                                            >{{ response.user.name }}</span
                                        >
                                        <span
                                            v-if="response.is_internal"
                                            class="ml-2 rounded bg-yellow-200 px-2 py-1 text-xs text-yellow-800"
                                            >Nota Interna</span
                                        >
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">{{
                                            new Date(
                                                response.created_at,
                                            ).toLocaleString('es-ES')
                                        }}</span>
                                        <button
                                            v-if="
                                                response.user_id === user.id ||
                                                user.role === 'superadmin'
                                            "
                                            @click="deleteResponse(response.id)"
                                            class="text-sm text-red-600 hover:text-red-900"
                                        >
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                                <p class="whitespace-pre-wrap text-gray-700">
                                    {{ response.message }}
                                </p>
                            </div>
                        </div>

                        <div v-else class="py-8 text-center text-gray-500">
                            <p>Aún no hay respuestas en este ticket.</p>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Respuesta -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <button
                            v-if="!showResponseForm"
                            @click="showResponseForm = true"
                            class="w-full rounded-lg bg-indigo-600 px-4 py-3 font-medium text-white transition hover:bg-indigo-700"
                        >
                            + Agregar Respuesta
                        </button>

                        <form
                            v-if="showResponseForm"
                            @submit.prevent="submitResponse"
                            class="space-y-4"
                        >
                            <div>
                                <label
                                    for="message"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                    >Tu respuesta</label
                                >
                                <textarea
                                    id="message"
                                    v-model="responseForm.message"
                                    rows="4"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Escribe tu respuesta aquí..."
                                    required
                                ></textarea>
                                <InputError
                                    class="mt-2"
                                    :message="responseForm.errors.message"
                                />
                            </div>

                            <div
                                v-if="canRespondTickets"
                                class="flex items-center"
                            >
                                <input
                                    id="is_internal"
                                    v-model="responseForm.is_internal"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                <label
                                    for="is_internal"
                                    class="ml-2 block text-sm text-gray-700"
                                    >Marcar como nota interna (solo visible para
                                    administradores)</label
                                >
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <button
                                    type="button"
                                    @click="
                                        showResponseForm = false;
                                        responseForm.reset();
                                    "
                                    class="rounded-md bg-gray-300 px-4 py-2 font-medium text-gray-700 transition hover:bg-gray-400"
                                >
                                    Cancelar
                                </button>
                                <PrimaryButton
                                    :class="{
                                        'opacity-25': responseForm.processing,
                                    }"
                                    :disabled="responseForm.processing"
                                    >Enviar Respuesta</PrimaryButton
                                >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
