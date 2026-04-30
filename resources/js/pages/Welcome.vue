<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Welcome" />

    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4"
    >
        <div
            class="w-full max-w-md rounded-lg bg-white p-8 text-center shadow-lg"
        >
            <!-- Logo -->
            <div class="mb-8">
                <ApplicationLogo class="mx-auto h-40 w-40" />
            </div>

            <!-- Título -->
            <h1 class="mb-2 text-3xl font-bold text-gray-900">
                Sistema de Gestión de Red
            </h1>
            <p class="mb-8 text-gray-600">
                Gestiona dispositivos de red, tickets de soporte y usuarios de
                manera eficiente.
            </p>

            <!-- Botones de acción -->
            <div class="space-y-4">
                <Link
                    href="/login"
                    class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white transition duration-150 ease-in-out hover:bg-indigo-700"
                >
                    Iniciar Sesión
                </Link>

                <Link
                    href="/register"
                    class="inline-flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50"
                >
                    Registrarse
                </Link>
            </div>

            <!-- Características -->
            <div class="mt-8 border-t border-gray-200 pt-8">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">
                    Características
                </h2>
                <div class="grid grid-cols-1 gap-3 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg
                            class="mr-2 h-5 w-5 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                        Gestión de dispositivos de red
                    </div>
                    <div class="flex items-center">
                        <svg
                            class="mr-2 h-5 w-5 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                        Sistema de tickets de soporte
                    </div>
                    <div class="flex items-center">
                        <svg
                            class="mr-2 h-5 w-5 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                        Control de usuarios y roles
                    </div>
                    <div class="flex items-center">
                        <svg
                            class="mr-2 h-5 w-5 text-green-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                        Panel de administración
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>
                &copy; 2025 Sistema de Gestión de Red. Todos los derechos
                reservados.
            </p>
        </div>
    </div>
</template>
