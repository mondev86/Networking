<script setup lang="ts">
import type { PageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<PageProps>();

const flash = computed(() => ({
    success: page.props.flash?.success,
    error: page.props.flash?.error,
}));
</script>

<template>
    <div class="fixed right-4 top-4 z-50 space-y-2">
        <!-- Mensaje de éxito -->
        <div
            v-if="flash.success"
            class="flex max-w-md items-start rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800 shadow-lg"
        >
            <svg
                class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                />
            </svg>
            <div>
                <p class="font-medium">{{ flash.success }}</p>
            </div>
        </div>

        <!-- Mensaje de error -->
        <div
            v-if="flash.error"
            class="flex max-w-md items-start rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 shadow-lg"
        >
            <svg
                class="mr-3 mt-0.5 h-5 w-5 flex-shrink-0"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"
                />
            </svg>
            <div>
                <p class="font-medium">{{ flash.error }}</p>
            </div>
        </div>
    </div>
</template>
