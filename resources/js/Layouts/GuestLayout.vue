<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Left panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-between bg-gradient-to-br from-primary-600 via-primary-700 to-accent-700 p-12 relative overflow-hidden">
            <div class="absolute inset-0 -z-0">
                <div class="absolute left-0 top-0 h-96 w-96 rounded-full bg-white/5 blur-3xl" />
                <div class="absolute bottom-0 right-0 h-64 w-64 rounded-full bg-accent-500/10 blur-3xl" />
            </div>

            <div class="relative z-10">
                <Link href="/" class="inline-block">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12L12 2l10 10" />
                                <path d="M12 2v20" />
                                <path d="M7 7l5 5 5-5" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white">
                            Vision<span class="text-primary-200">Flow</span>
                        </span>
                    </div>
                </Link>
            </div>

            <div class="relative z-10">
                <h2 class="text-3xl font-bold text-white leading-tight">
                    {{ t('landing.hero.title') }}
                    <span class="text-primary-200"> {{ t('landing.hero.titleHighlight') }}</span>
                </h2>
                <p class="mt-4 max-w-md text-primary-100/80 leading-relaxed">
                    {{ t('landing.hero.subtitle') }}
                </p>

                <div class="mt-10 flex items-center gap-3">
                    <div class="flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-xs font-medium text-primary-100 backdrop-blur-sm">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        {{ t('landing.traceability.values') }}
                    </div>
                    <svg class="h-4 w-4 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    <div class="flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-xs font-medium text-primary-100 backdrop-blur-sm">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        {{ t('landing.traceability.vision') }}
                    </div>
                    <svg class="h-4 w-4 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                    <div class="flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-xs font-medium text-primary-100 backdrop-blur-sm">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>
                        {{ t('landing.traceability.missions') }}
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-sm text-primary-200/60">
                &copy; {{ new Date().getFullYear() }} VisionFlow
            </div>
        </div>

        <!-- Right panel - Form -->
        <div class="flex w-full flex-col lg:w-1/2">
            <div class="flex items-center justify-between px-6 py-4 lg:px-8">
                <Link href="/" class="lg:hidden">
                    <ApplicationLogo />
                </Link>
                <div class="ml-auto">
                    <LanguageSwitcher />
                </div>
            </div>

            <div class="flex flex-1 items-center justify-center px-6 py-8 lg:px-8">
                <div class="w-full max-w-md">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
