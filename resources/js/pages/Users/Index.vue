<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { User } from '@/types';
import { getRoleBadgeClass, getRoleLabel } from '@/Utils/helpers';
import { Head, Link, router } from '@inertiajs/vue3';

interface Props {
    users: User[];
}

defineProps<Props>();

const deleteUser = (user: User): void => {
    if (confirm(`¿Estás seguro de eliminar a ${user.name}?`)) {
        router.delete(route('users.destroy', user.id));
    }
};
</script>

<template>
    <Head title="Usuarios" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Gestión de Usuarios
                </h2>
                <Link
                    :href="route('users.create')"
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
                    Crear Usuario
                </Link>
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
                                            Nombre
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Email
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                        >
                                            Rol
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
                                        v-for="user in users"
                                        :key="user.id"
                                        class="hover:bg-gray-50"
                                    >
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ user.name }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <div class="text-sm text-gray-600">
                                                {{ user.email }}
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-6 py-4">
                                            <span
                                                :class="
                                                    getRoleBadgeClass(user.role)
                                                "
                                                class="inline-flex rounded-full px-2 py-1 text-xs font-semibold leading-5"
                                            >
                                                {{ getRoleLabel(user.role) }}
                                            </span>
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-sm text-gray-600"
                                        >
                                            {{
                                                new Date(
                                                    user.created_at,
                                                ).toLocaleDateString('es-ES')
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium"
                                        >
                                            <Link
                                                :href="
                                                    route('users.show', user.id)
                                                "
                                                class="mr-3 text-indigo-600 hover:text-indigo-900"
                                                >Ver</Link
                                            >
                                            <Link
                                                :href="
                                                    route('users.edit', user.id)
                                                "
                                                class="mr-3 text-blue-600 hover:text-blue-900"
                                                >Editar</Link
                                            >
                                            <button
                                                @click="deleteUser(user)"
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
                            v-if="users.length === 0"
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
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">
                                No hay usuarios
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Comienza creando un nuevo usuario.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
