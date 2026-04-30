<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                {{ device.model }} - {{ device.serial_number }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <!-- Información General -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            Información General
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">
                                    Número de Serie
                                </p>
                                <p class="font-mono text-lg">
                                    {{ device.serial_number }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Modelo</p>
                                <p class="text-lg">{{ device.model }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tipo</p>
                                <p class="text-lg">{{ device.type }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Estado</p>
                                <p class="text-lg">
                                    <span
                                        class="inline-block rounded-full px-3 py-1 text-xs font-semibold"
                                        :class="getStatusClass(device.status)"
                                    >
                                        {{ device.status }}
                                    </span>
                                </p>
                            </div>
                            <div v-if="device.purchase_date">
                                <p class="text-sm text-gray-600">
                                    Fecha de Compra
                                </p>
                                <p class="text-lg">
                                    {{ formatDate(device.purchase_date) }}
                                </p>
                            </div>
                            <div v-if="device.warranty_until">
                                <p class="text-sm text-gray-600">
                                    Garantía hasta
                                </p>
                                <p class="text-lg">
                                    {{ formatDate(device.warranty_until) }}
                                </p>
                            </div>
                        </div>
                        <div
                            v-if="device.description"
                            class="mt-4 border-t pt-4"
                        >
                            <p class="mb-2 text-sm text-gray-600">
                                Descripción
                            </p>
                            <p class="text-gray-700">
                                {{ device.description }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Asignación Actual -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="border-b border-gray-200 bg-white p-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            Asignación Actual
                        </h3>
                        <div v-if="device.current_assignment" class="space-y-4">
                            <div class="rounded-lg bg-blue-50 p-4">
                                <p class="text-sm text-gray-600">Asignado a</p>
                                <p class="text-lg font-semibold">
                                    {{
                                        device.current_assignment.assignable
                                            ?.name
                                    }}
                                </p>
                                <p class="mt-2 text-sm text-gray-600">
                                    Asignado por:
                                    {{
                                        device.current_assignment.assigned_by
                                            ?.name
                                    }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Fecha:
                                    {{
                                        formatDate(
                                            device.current_assignment
                                                .assigned_date,
                                        )
                                    }}
                                </p>
                                <p
                                    v-if="device.current_assignment.notes"
                                    class="mt-2 text-sm text-gray-600"
                                >
                                    Notas: {{ device.current_assignment.notes }}
                                </p>
                            </div>

                            <button
                                v-if="isAdmin"
                                @click="unassign"
                                :disabled="unassignLoading"
                                class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700 disabled:bg-gray-400"
                            >
                                {{
                                    unassignLoading
                                        ? 'Desasignando...'
                                        : 'Desasignar'
                                }}
                            </button>
                        </div>
                        <div v-else>
                            <p class="mb-4 text-gray-600">
                                Este dispositivo no está asignado.
                            </p>
                            <button
                                v-if="isAdmin"
                                @click="showAssignModal = true"
                                class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                            >
                                Asignar Ahora
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Historial de Asignaciones -->
                <div
                    v-if="assignmentHistory.length > 0"
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="border-b border-gray-200 bg-white p-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            Historial de Asignaciones
                        </h3>
                        <div class="space-y-4">
                            <div
                                v-for="assignment in assignmentHistory"
                                :key="assignment.id"
                                class="rounded-lg bg-gray-50 p-4"
                            >
                                <p class="font-semibold">
                                    {{ assignment.assignable?.name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ formatDate(assignment.assigned_date) }} -
                                    {{
                                        assignment.unassigned_date
                                            ? formatDate(
                                                  assignment.unassigned_date,
                                              )
                                            : 'Actualmente'
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex gap-4">
                    <Link
                        v-if="isAdmin"
                        :href="route('equipment.edit', device.id)"
                        class="rounded-lg bg-yellow-600 px-4 py-2 text-white hover:bg-yellow-700"
                    >
                        Editar
                    </Link>
                    <button
                        v-if="isAdmin"
                        @click="deleteDevice"
                        class="rounded-lg bg-red-600 px-4 py-2 text-white hover:bg-red-700"
                    >
                        Eliminar
                    </button>
                    <Link
                        :href="route('equipment.index')"
                        class="rounded-lg bg-gray-600 px-4 py-2 text-white hover:bg-gray-700"
                    >
                        Volver
                    </Link>
                </div>
            </div>
        </div>

        <!-- ✅ MODAL DE ASIGNACIÓN - SOLO USUARIOS -->
        <div
            v-if="showAssignModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
            @click="showAssignModal = false"
        >
            <div
                class="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-lg"
                @click.stop
            >
                <h3 class="mb-4 text-lg font-semibold">
                    Asignar Dispositivo a Usuario
                </h3>

                <form @submit.prevent="handleSubmitAssign" class="space-y-4">
                    <!-- Seleccionar Usuario -->
                    <div>
                        <label
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Seleccionar Usuario *
                        </label>
                        <select
                            v-model="assignForm.assignable_id"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            required
                        >
                            <option value="">-- Seleccionar usuario --</option>
                            <option
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>
                        </select>
                        <p
                            v-if="users.length === 0"
                            class="mt-1 text-sm text-red-600"
                        >
                            ⚠️ No hay usuarios disponibles
                        </p>
                        <p v-else class="mt-1 text-sm text-gray-500">
                            {{ users.length }} usuario(s) disponible(s)
                        </p>
                    </div>

                    <!-- Notas -->
                    <div>
                        <label
                            class="mb-2 block text-sm font-medium text-gray-700"
                        >
                            Notas (Opcional)
                        </label>
                        <textarea
                            v-model="assignForm.notes"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                            rows="3"
                            placeholder="Ej: Asignado para proyecto X..."
                        ></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3">
                        <button
                            type="submit"
                            :disabled="
                                assignForm.processing || users.length === 0
                            "
                            class="flex-1 rounded-lg bg-green-600 px-4 py-2 font-semibold text-white hover:bg-green-700 disabled:cursor-not-allowed disabled:bg-gray-400"
                        >
                            {{
                                assignForm.processing
                                    ? 'Asignando...'
                                    : 'Asignar'
                            }}
                        </button>
                        <button
                            type="button"
                            @click="showAssignModal = false"
                            class="flex-1 rounded-lg bg-gray-300 px-4 py-2 font-semibold text-gray-700 hover:bg-gray-400"
                        >
                            Cancelar
                        </button>
                    </div>

                    <!-- Debug Info -->
                    <div
                        v-if="debugMode"
                        class="mt-4 rounded bg-gray-100 p-3 text-xs text-gray-600"
                    >
                        <p><strong>Debug:</strong></p>
                        <p>Usuarios cargados: {{ users.length }}</p>
                        <p>Tipo seleccionado: "user"</p>
                        <p>
                            ID seleccionado:
                            {{ assignForm.assignable_id || 'Ninguno' }}
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Device {
    id: number;
    serial_number: string;
    model: string;
    type: string;
    status: string;
    purchase_date?: string;
    warranty_until?: string;
    description?: string;
    current_assignment?: any;
}

interface Props {
    device: Device;
    assignmentHistory: any[];
    isAdmin: boolean;
    users: any[]; // ✅ Llega como prop desde el controller
}

const props = defineProps<Props>();

const showAssignModal = ref(false);
const unassignLoading = ref(false);
const debugMode = ref(false); // ✅ Debug desactivado

const assignForm = useForm({
    assignable_type: 'user',
    assignable_id: '',
    notes: '',
});

// ========== FUNCIONES ==========

const getStatusClass = (status: string): string => {
    const classes: Record<string, string> = {
        available: 'bg-green-100 text-green-800',
        in_use: 'bg-blue-100 text-blue-800',
        broken: 'bg-red-100 text-red-800',
        retired: 'bg-gray-100 text-gray-800',
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (date: string): string => {
    return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const handleSubmitAssign = (): void => {
    assignForm.post(route('equipment.assign', props.device.id), {
        onSuccess: () => {
            showAssignModal.value = false;
            assignForm.reset();
        },
    });
};

const unassign = (): void => {
    unassignLoading.value = true;
    router.post(route('equipment.unassign', props.device.id), {
        onFinish: () => {
            unassignLoading.value = false;
        },
    });
};

const deleteDevice = (): void => {
    if (confirm('¿Estás seguro?')) {
        router.delete(route('equipment.destroy', props.device.id));
    }
};
</script>
