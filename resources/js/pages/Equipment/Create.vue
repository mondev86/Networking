<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">Nuevo Equipo Informático</h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submitForm" class="space-y-6">

              <!-- Serial Number -->
              <div>
                <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">
                  Número de Serie *
                </label>
                <input
                  id="serial_number"
                  v-model="form.serial_number"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg"
                  :class="form.errors.serial_number ? 'border-red-500' : 'border-gray-300'"
                  required
                />
                <p v-if="form.errors.serial_number" class="text-red-600 text-sm mt-1">
                  {{ form.errors.serial_number }}
                </p>
              </div>

              <!-- Model -->
              <div>
                <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                  Modelo *
                </label>
                <input
                  id="model"
                  v-model="form.model"
                  type="text"
                  class="w-full px-4 py-2 border rounded-lg"
                  :class="form.errors.model ? 'border-red-500' : 'border-gray-300'"
                  required
                />
                <p v-if="form.errors.model" class="text-red-600 text-sm mt-1">
                  {{ form.errors.model }}
                </p>
              </div>

              <!-- Type -->
              <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                  Tipo *
                </label>
                <select
                  id="type"
                  v-model="form.type"
                  class="w-full px-4 py-2 border rounded-lg"
                  :class="form.errors.type ? 'border-red-500' : 'border-gray-300'"
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
                <p v-if="form.errors.type" class="text-red-600 text-sm mt-1">
                  {{ form.errors.type }}
                </p>
              </div>

              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                  Estado *
                </label>
                <select
                  id="status"
                  v-model="form.status"
                  class="w-full px-4 py-2 border rounded-lg"
                  :class="form.errors.status ? 'border-red-500' : 'border-gray-300'"
                  required
                >
                  <option value="">Seleccionar estado</option>
                  <option value="available">Disponible</option>
                  <option value="in_use">En uso</option>
                  <option value="broken">Roto</option>
                  <option value="retired">Retirado</option>
                </select>
                <p v-if="form.errors.status" class="text-red-600 text-sm mt-1">
                  {{ form.errors.status }}
                </p>
              </div>

              <!-- Purchase Date -->
              <div>
                <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">
                  Fecha de Compra
                </label>
                <input
                  id="purchase_date"
                  v-model="form.purchase_date"
                  type="date"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                />
              </div>

              <!-- Warranty Until -->
              <div>
                <label for="warranty_until" class="block text-sm font-medium text-gray-700 mb-2">
                  Garantía hasta
                </label>
                <input
                  id="warranty_until"
                  v-model="form.warranty_until"
                  type="date"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                />
              </div>

              <!-- Description -->
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                  Descripción
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                  rows="4"
                ></textarea>
              </div>

              <!-- ✅ SECCIÓN ASIGNACIÓN OPCIONAL -->
              <div class="border-t pt-6">
                <div class="flex items-center gap-3 mb-4">
                  <input
                    id="assign_now"
                    v-model="assignNow"
                    type="checkbox"
                    class="w-4 h-4 text-green-600 rounded"
                  />
                  <label for="assign_now" class="text-sm font-medium text-gray-700 cursor-pointer">
                    Asignar al crear
                  </label>
                </div>

                <div v-if="assignNow" class="space-y-4 bg-green-50 p-4 rounded-lg">

                  <!-- Tipo de asignación -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Asignar a *
                    </label>
                    <div class="flex gap-3">
                      <button
                        v-for="tab in assignTabs"
                        :key="tab.value"
                        type="button"
                        @click="selectAssignType(tab.value)"
                        class="flex-1 py-2 px-3 rounded-lg text-sm font-semibold border-2 transition-colors"
                        :class="form.assignable_type === tab.value
                          ? 'border-green-600 bg-green-600 text-white'
                          : 'border-gray-300 bg-white text-gray-600 hover:border-green-400'"
                      >
                        {{ tab.label }}
                      </button>
                    </div>
                  </div>

                  <!-- Lista según tipo seleccionado -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Seleccionar {{ currentTabLabel }} *
                    </label>

                    <select
                      v-model="form.assignable_id"
                      class="w-full px-4 py-2 border rounded-lg"
                      :class="form.errors.assignable_id ? 'border-red-500' : 'border-gray-300'"
                      :required="assignNow"
                    >
                      <option value="">-- Seleccionar {{ currentTabLabel }} --</option>
                      <option v-for="item in currentTargets" :key="item.id" :value="item.id">
                        {{ item.name }}
                      </option>
                    </select>

                    <p v-if="form.errors.assignable_id" class="text-red-600 text-sm mt-1">
                      {{ form.errors.assignable_id }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                      {{ currentTargets.length }} {{ currentTabLabel.toLowerCase() }}(s) disponible(s)
                    </p>
                  </div>

                  <!-- Notas -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                      Notas (Opcional)
                    </label>
                    <textarea
                      v-model="form.assignment_notes"
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg"
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
                  class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
                >
                  {{ form.processing ? 'Creando...' : (assignNow ? 'Crear y Asignar' : 'Crear Equipo') }}
                </button>
                <Link
                  :href="route('equipment.index')"
                  class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700"
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
import { ref, computed, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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
  { value: 'user',       label: 'Usuario'      },
  { value: 'department', label: 'Departamento' },
  { value: 'team',       label: 'Equipo'       },
];

const form = useForm({
  serial_number:    '',
  model:            '',
  type:             '',
  status:           'available',
  purchase_date:    '',
  warranty_until:   '',
  description:      '',
  assignable_type:  'user',
  assignable_id:    '',
  assignment_notes: '',
});

// ========== COMPUTED ==========
const currentTabLabel = computed(() => {
  return assignTabs.find(t => t.value === form.assignable_type)?.label ?? '';
});

const currentTargets = computed(() => {
  switch (form.assignable_type) {
    case 'user':       return props.users;
    case 'department': return props.departments;
    case 'team':       return props.teams;
    default:           return [];
  }
});

// ========== MÉTODOS ==========
const selectAssignType = (type: string): void => {
  form.assignable_type = type;
  form.assignable_id = '';
};

watch(assignNow, (val) => {
  if (!val) {
    form.assignable_type  = 'user';
    form.assignable_id    = '';
    form.assignment_notes = '';
  }
});

const submitForm = (): void => {
  if (!assignNow.value) {
    form.assignable_type  = '';
    form.assignable_id    = '';
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
