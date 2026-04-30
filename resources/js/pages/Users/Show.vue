<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { User } from '@/types';
import { getRoleBadgeClass, getRoleLabel } from '@/Utils/helpers';
import { Head, Link } from '@inertiajs/vue3';

interface Props {
    user: User;
}

defineProps<Props>();
</script>

<template>
    <Head :title="`Usuario: ${user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detalles del Usuario
                </h2>
                <div class="flex gap-2">
                    <Link
                        :href="route('users.edit', user.id)"
                        class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-blue-700"
                    >
                        Editar
                    </Link>
                    <Link
                        :href="route('users.index')"
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700"
                    >
                        ← Volver
                    </Link>
                </div>
            </div>
        </template>

        <FlashMessages />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold">
                            Información del Usuario
                        </h3>
                        <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">
                                    Nombre
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">
                                    Email
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ user.email }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">
                                    Rol
                                </dt>
                                <dd class="mt-1">
                                    <span
                                        :class="getRoleBadgeClass(user.role)"
                                        class="rounded-full px-2 py-1 text-xs font-medium"
                                    >
                                        {{ getRoleLabel(user.role) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">
                                    Fecha de Registro
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{
                                        new Date(
                                            user.created_at,
                                        ).toLocaleDateString('es-ES', {
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric',
                                        })
                                    }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
