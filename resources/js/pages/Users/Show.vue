<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import type { User } from '@/types';
import { getRoleBadgeClass, getRoleLabel } from '@/Utils/helpers';

interface Props {
    user: User;
}

defineProps<Props>();
</script>

<template>
    <Head :title="`Usuario: ${user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalles del Usuario</h2>
                <div class="flex gap-2">
                    <Link :href="route('users.edit', user.id)" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Editar
                    </Link>
                    <Link :href="route('users.index')" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        ← Volver
                    </Link>
                </div>
            </div>
        </template>

        <FlashMessages />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Información del Usuario</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ user.email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rol</dt>
                                <dd class="mt-1">
                                    <span :class="getRoleBadgeClass(user.role)" class="px-2 py-1 text-xs rounded-full font-medium">
                                        {{ getRoleLabel(user.role) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ new Date(user.created_at).toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
