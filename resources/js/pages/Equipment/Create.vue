<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">
                Nuevo Equipo Informático
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Serial Number -->
                            <div>
                                <label
                                    for="serial_number"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Número de Serie *
                                </label>
                                <input
                                    id="serial_number"
                                    v-model="form.serial_number"
                                    type="text"
                                    class="w-full rounded-lg border px-4 py-2"
                                    :class="
                                        form.errors.serial_number
                                            ? 'border-red-500'
                                            : 'border-gray-300'
                                    "
                                    required
                                />
                                <p
                                    v-if="form.errors.serial_number"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.serial_number }}
                                </p>
                            </div>

                            <!-- Model -->
                            <div>
                                <label
                                    for="model"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Modelo *
                                </label>
                                <input
                                    id="model"
                                    v-model="form.model"
                                    type="text"
                                    class="w-full rounded-lg border px-4 py-2"
                                    :class="
                                        form.errors.model
                                            ? 'border-red-500'
                                            : 'border-gray-300'
                                    "
                                    required
                                />
                                <p
                                    v-if="form.errors.model"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.model }}
                                </p>
                            </div>

                            <!-- Type -->
                            <div>
                                <label
                                    for="type"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Tipo *
                                </label>
                                <select
                                    id="type"
                                    v-model="form.type"
                                    class="w-full rounded-lg border px-4 py-2"
                                    :class="
                                        form.errors.type
                                            ? 'border-red-500'
                                            : 'border-gray-300'
                                    "
                                    required
                                >
                                    <option value="">Seleccionar tipo</option>
                                    <option value="laptop">Laptop</option>
                                    <option value="monitor">Monitor</option>
                                    <option value="keyboard">Teclado</option>
                                    <option value="mouse">Ratón</option>
                                    <option value="printer">Impresora</option>
                                    <option value="other">Otro</option>
                                </select>
                                <p
                                    v-if="form.errors.type"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.type }}
                                </p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label
                                    for="status"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Estado *
                                </label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="w-full rounded-lg border px-4 py-2"
                                    :class="
                                        form.errors.status
                                            ? 'border-red-500'
                                            : 'border-gray-300'
                                    "
                                    required
                                >
                                    <option value="">Seleccionar estado</option>
                                    <option value="available">
                                        Disponible
                                    </option>
                                    <option value="in_use">En uso</option>
                                    <option value="broken">Roto</option>
                                    <option value="retired">Retirado</option>
                                </select>
                                <p
                                    v-if="form.errors.status"
                                    class="mt-1 text-sm text-red-600"
                                >
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <!-- Purchase Date -->
                            <div>
                                <label
                                    for="purchase_date"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Fecha de Compra
                                </label>
                                <input
                                    id="purchase_date"
                                    v-model="form.purchase_date"
                                    type="date"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2"
                                />
                            </div>

                            <!-- Warranty Until -->
                            <div>
                                <label
                                    for="warranty_until"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Garantía hasta
                                </label>
                                <input
                                    id="warranty_until"
                                    v-model="form.warranty_until"
                                    type="date"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2"
                                />
                            </div>

                            <!-- Description -->
                            <div>
                                <label
                                    for="description"
                                    class="mb-2 block text-sm font-medium text-gray-700"
                                >
                                    Descripción
                                </label>
                                <textarea
                                    id="description"
                                    v-model="form.description"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2"
                                    rows="4"
                                ></textarea>
                            </div>

                            <!-- ✅ SECCIÓN ASIGNACIÓN OPCIONAL -->
                            <div class="border-t pt-6">
                                <div class="mb-4 flex items-center gap-3">
                                    <input
                                        id="assign_now"
                                        v-model="assignNow"
                                        type="checkbox"
                                        class="h-4 w-4 rounded text-green-600"
                                    />
                                    <label
                                        for="assign_now"
                                        class="cursor-pointer text-sm font-medium text-gray-700"
                                    >
                                        Asignar al crear
                                    </label>
                                </div>

                                <div
                                    v-if="assignNow"
                                    class="space-y-4 rounded-lg bg-green-50 p-4"
                                >
                                    <!-- Tipo de asignación -->
                                    <div>
                                        <label
                                            class="mb-2 block text-sm font-medium text-gray-700"
                                        >
                                            Asignar a *
                                        </label>
                                        <div class="flex gap-3">
                                            <button
                                                v-for="tab in assignTabs"
                                                :key="tab.value"
                                                type="button"
                                                @click="
                                                    selectAssignType(tab.value)
                                                "
                                                class="flex-1 rounded-lg border-2 px-3 py-2 text-sm font-semibold transition-colors"
                                                :class="
                                                    form.assignable_type ===
                                                    tab.value
                                                        ? 'border-green-600 bg-green-600 text-white'
                                                        : 'border-gray-300 bg-white text-gray-600 hover:border-green-400'
                                                "
                                            >
                                                {{ tab.label }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Lista según tipo seleccionado -->
                                    <div>
                                        <label
                                            class="mb-2 block text-sm font-medium text-gray-700"
                                        >
                                            Seleccionar {{ currentTabLabel }} *
                                        </label>

                                        <select
                                            v-model="form.assignable_id"
                                            class="w-full rounded-lg border px-4 py-2"
                                            :class="
                                                form.errors.assignable_id
                                                    ? 'border-red-500'
                                                    : 'border-gray-300'
                                            "
                                            :required="assignNow"
                                        >
                                            <option value="">
                                                -- Seleccionar
                                                {{ currentTabLabel }} --
                                            </option>
                                            <option
                                                v-for="item in currentTargets"
                                                :key="item.id"
                                                :value="item.id"
                                            >
                                                {{ item.name }}
                                            </option>
                                        </select>

                                        <p
                                            v-if="form.errors.assignable_id"
                                            class="mt-1 text-sm text-red-600"
                                        >
                                            {{ form.errors.assignable_id }}
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ currentTargets.length }}
                                            {{
                                                currentTabLabel.toLowerCase()
                                            }}(s) disponible(s)
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
                                            v-model="form.assignment_notes"
                                            class="w-full rounded-lg border border-gray-300 px-4 py-2"
                                            rows="2"
                                            placeholder="Ej: Asignado para proyecto X..."
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-4">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 disabled:bg-gray-400"
                                >
                                    {{
                                        form.processing
                                            ? 'Creando...'
                                            : assignNow
                                              ? 'Crear y Asignar'
                                              : 'Crear Equipo'
                                    }}
                                </button>
                                <Link
                                    :href="route('equipment.index')"
                                    class="rounded-lg bg-gray-600 px-4 py-2 text-white hover:bg-gray-700"
                                >
                                    Cancelar
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

// ✅ Recibir datos via Inertia (no fetch)
interface Props {
    users: any[];
    departments: any[];
    teams: any[];
}
const props = defineProps<Props>();

// ========== ESTADO ==========
const assignNow = ref(false);

const assignTabs = [
    { value: 'user', label: 'Usuario' },
    { value: 'department', label: 'Departamento' },
    { value: 'team', label: 'Equipo' },
];

const form = useForm({
    serial_number: '',
    model: '',
    type: '',
    status: 'available',
    purchase_date: '',
    warranty_until: '',
    description: '',
    assignable_type: 'user',
    assignable_id: '',
    assignment_notes: '',
});

// ========== COMPUTED ==========
const currentTabLabel = computed(() => {
    return (
        assignTabs.find((t) => t.value === form.assignable_type)?.label ?? ''
    );
});

const currentTargets = computed(() => {
    switch (form.assignable_type) {
        case 'user':
            return props.users;
        case 'department':
            return props.departments;
        case 'team':
            return props.teams;
        default:
            return [];
    }
});

// ========== MÉTODOS ==========
const selectAssignType = (type: string): void => {
    form.assignable_type = type;
    form.assignable_id = '';
};

watch(assignNow, (val) => {
    if (!val) {
        form.assignable_type = 'user';
        form.assignable_id = '';
        form.assignment_notes = '';
    }
});

const submitForm = (): void => {
    if (!assignNow.value) {
        form.assignable_type = '';
        form.assignable_id = '';
        form.assignment_notes = '';
    }

    form.post(route('equipment.store'), {
        onSuccess: () => {
            form.reset();
            assignNow.value = false;
        },
    });
};
</script>
