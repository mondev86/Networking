<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">
          Equipos Informáticos
        </h2>
        <Link
          :href="route('equipment.create')"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
        >
          ➕ Nuevo Equipo
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- Tabla de Dispositivos -->
            <div class="overflow-x-auto">
              <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-700 font-semibold">
                  <tr>
                    <th class="px-6 py-3">Serie</th>
                    <th class="px-6 py-3">Modelo</th>
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3">Estado</th>
                    <th class="px-6 py-3">Asignado a</th>
                    <th class="px-6 py-3">Acciones</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="device in devices.data" :key="device.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-xs text-gray-600">
                      {{ device.serial_number }}
                    </td>
                    <td class="px-6 py-4">{{ device.model }}</td>
                    <td class="px-6 py-4">
                      <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                        :class="getTypeClass(device.type)"
                      >
                        {{ device.type }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold"
                        :class="getStatusClass(device.status)"
                      >
                        {{ device.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4">
                      <span v-if="device.current_assignment" class="text-sm text-gray-700">
                        {{ device.current_assignment?.assignable?.name || 'N/A' }}
                      </span>
                      <span v-else class="text-sm text-gray-400">Sin asignar</span>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                      <Link
                        :href="route('equipment.show', device.id)"
                        class="text-blue-600 hover:text-blue-800 font-semibold"
                      >
                        Ver
                      </Link>
                      <Link
                        :href="route('equipment.edit', device.id)"
                        class="text-yellow-600 hover:text-yellow-800 font-semibold"
                      >
                        Editar
                      </Link>
                      <button
                        @click="deleteDevice(device.id)"
                        class="text-red-600 hover:text-red-800 font-semibold"
                      >
                        Eliminar
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Paginación -->
<div v-if="devices.links" class="mt-6 flex justify-center gap-2">
  <Link
    v-for="link in devices.links"
    :key="link.url + link.label"
    :href="link.url || '#'"
    :class="{
      'px-3 py-1 rounded border bg-blue-600 text-white': link.active,
      'px-3 py-1 rounded border bg-white text-gray-700 border-gray-300': !link.active,
      'opacity-50 cursor-not-allowed': !link.url,
    }"
    :disabled="!link.url"
  >
    <span v-html="link.label"></span>
  </Link>
</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

interface Device {
  id: number;
  serial_number: string;
  model: string;
  type: string;
  status: string;
  current_assignment?: {
    assignable?: {
      name: string;
    };
  };
}

interface Props {
  devices: {
    data: Device[];
    links: any[];
  };
}

defineProps<Props>();

const getTypeClass = (type: string): string => {
  const classes: Record<string, string> = {
    laptop: 'bg-blue-100 text-blue-800',
    monitor: 'bg-purple-100 text-purple-800',
    printer: 'bg-orange-100 text-orange-800',
    keyboard: 'bg-green-100 text-green-800',
    mouse: 'bg-pink-100 text-pink-800',
    other: 'bg-gray-100 text-gray-800',
  };
  return classes[type] || 'bg-gray-100 text-gray-800';
};

const getStatusClass = (status: string): string => {
  const classes: Record<string, string> = {
    available: 'bg-green-100 text-green-800',
    in_use: 'bg-blue-100 text-blue-800',
    broken: 'bg-red-100 text-red-800',
    retired: 'bg-gray-100 text-gray-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const deleteDevice = (deviceId: number): void => {
  if (confirm('¿Estás seguro de que quieres eliminar este dispositivo?')) {
    router.delete(route('equipment.destroy', deviceId));
  }
};
</script>
