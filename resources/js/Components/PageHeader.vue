<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    title: string;
    subtitle?: string;
    createRoute?: string;
    createLabel?: string;
    backRoute?: string;
    backLabel?: string;
}>();
</script>

<template>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div v-if="backRoute" class="mb-2">
                <Link :href="backRoute" class="inline-flex items-center gap-1 text-sm text-gray-500 transition hover:text-gray-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    {{ backLabel || 'Back' }}
                </Link>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ title }}</h1>
            <p v-if="subtitle" class="mt-1 text-sm text-gray-500">{{ subtitle }}</p>
        </div>
        <div v-if="createRoute">
            <Link :href="createRoute" class="btn-primary">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                {{ createLabel || 'Create' }}
            </Link>
        </div>
        <slot name="actions" />
    </div>
</template>
