<script setup lang="ts">
defineProps<{
    class?: string;
    showText?: boolean;
}>();
</script>

<template>
    <div class="flex items-center gap-2.5" :class="$props.class">
        <div class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary-600 to-accent-600 shadow-lg shadow-primary-600/25">
            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 12L12 2l10 10" />
                <path d="M12 2v20" />
                <path d="M7 7l5 5 5-5" />
            </svg>
        </div>
        <span v-if="showText !== false" class="text-xl font-bold tracking-tight text-gray-900">
            Vision<span class="text-primary-600">Flow</span>
        </span>
    </div>
</template>
