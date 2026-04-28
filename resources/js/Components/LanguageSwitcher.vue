<script setup lang="ts">
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { locale } = useI18n();
const isOpen = ref(false);

const languages = [
    { code: 'en', label: 'English', flag: '🇬🇧' },
    { code: 'de', label: 'Deutsch', flag: '🇩🇪' },
] as const;

const currentLang = () => languages.find(l => l.code === locale.value) || languages[0];

const switchLocale = (code: string) => {
    locale.value = code;
    localStorage.setItem('visionflow-locale', code);
    isOpen.value = false;
};
</script>

<template>
    <div class="relative">
        <button
            @click="isOpen = !isOpen"
            class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-100 hover:text-gray-900"
        >
            <span class="text-base">{{ currentLang().flag }}</span>
            <span class="hidden sm:inline">{{ currentLang().label }}</span>
            <svg class="h-4 w-4 transition" :class="{ 'rotate-180': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="isOpen"
                class="absolute right-0 z-50 mt-2 w-40 origin-top-right rounded-xl border border-gray-100 bg-white py-1 shadow-lg"
            >
                <button
                    v-for="lang in languages"
                    :key="lang.code"
                    @click="switchLocale(lang.code)"
                    class="flex w-full items-center gap-3 px-4 py-2.5 text-sm transition hover:bg-gray-50"
                    :class="locale === lang.code ? 'text-primary-600 font-medium bg-primary-50/50' : 'text-gray-700'"
                >
                    <span class="text-base">{{ lang.flag }}</span>
                    <span>{{ lang.label }}</span>
                </button>
            </div>
        </transition>

        <div v-if="isOpen" class="fixed inset-0 z-40" @click="isOpen = false" />
    </div>
</template>
