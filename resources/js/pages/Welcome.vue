<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex flex-col justify-center items-center p-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Logo -->
            <div class="mb-8">
                <ApplicationLogo class="w-40 h-40 mx-auto" />
            </div>

            <!-- Título -->
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Sistema de Gestión de Red
            </h1>
            <p class="text-gray-600 mb-8">
                Gestiona dispositivos de red, tickets de soporte y usuarios de manera eficiente.
            </p>

            <!-- Botones de acción -->
            <div class="space-y-4">
                <Link
                    href="/login"
                    class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150 ease-in-out"
                >
                    Iniciar Sesión
                </Link>

                <Link
                    href="/register"
                    class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out"
                >
                    Registrarse
                </Link>
            </div>

            <!-- Características -->
            <div class="mt-8 pt-8 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Características</h2>
                <div class="grid grid-cols-1 gap-3 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Gestión de dispositivos de red
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Sistema de tickets de soporte
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Control de usuarios y roles
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Panel de administración
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>&copy; 2025 Sistema de Gestión de Red. Todos los derechos reservados.</p>
        </div>
    </div>
</template>
