<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { User } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';

interface Props {
    user: User;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.role,
});

const submit = (): void => {
    form.put(route('users.update', props.user.id));
};
</script>

<template>
    <Head :title="`Editar Usuario: ${user.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Editar Usuario: {{ user.name }}
                </h2>
                <Link
                    :href="route('users.index')"
                    class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700"
                >
                    ← Volver
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="space-y-6">
                            <div>
                                <InputLabel
                                    for="name"
                                    value="Nombre completo"
                                />
                                <TextInput
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    for="email"
                                    value="Correo electrónico"
                                />
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>

                            <div>
                                <InputLabel for="role" value="Rol" />
                                <select
                                    id="role"
                                    v-model="form.role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required
                                >
                                    <option value="user">Usuario</option>
                                    <option value="admin">Administrador</option>
                                    <option value="superadmin">
                                        Superadministrador
                                    </option>
                                </select>
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.role"
                                />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-4">
                            <Link
                                :href="route('users.index')"
                                class="inline-flex items-center rounded-md border border-transparent bg-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-400"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Actualizar Usuario
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
