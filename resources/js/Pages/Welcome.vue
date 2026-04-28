<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { ref } from 'vue';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
}>();

const { t } = useI18n();
const mobileMenuOpen = ref(false);
</script>

<template>
    <Head :title="t('app.tagline')" />

    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <nav class="fixed top-0 z-50 w-full border-b border-gray-100/80 bg-white/80 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <Link href="/">
                    <ApplicationLogo />
                </Link>

                <div class="hidden items-center gap-2 md:flex">
                    <a href="#features" class="btn-ghost">{{ t('nav.features') }}</a>
                    <a href="#how-it-works" class="btn-ghost">{{ t('nav.howItWorks') }}</a>
                    <LanguageSwitcher />
                    <template v-if="canLogin">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="btn-ghost">
                            {{ t('nav.dashboard') }}
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="btn-ghost">
                                {{ t('nav.login') }}
                            </Link>
                            <Link v-if="canRegister" :href="route('register')" class="btn-primary !py-2.5 !px-5 !text-sm">
                                {{ t('nav.register') }}
                            </Link>
                        </template>
                    </template>
                </div>

                <!-- Mobile menu button -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden rounded-lg p-2 text-gray-600 hover:bg-gray-100">
                    <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileMenuOpen" class="border-t border-gray-100 bg-white px-4 py-4 md:hidden">
                <div class="flex flex-col gap-2">
                    <a href="#features" @click="mobileMenuOpen = false" class="btn-ghost justify-start">{{ t('nav.features') }}</a>
                    <a href="#how-it-works" @click="mobileMenuOpen = false" class="btn-ghost justify-start">{{ t('nav.howItWorks') }}</a>
                    <div class="my-2 border-t border-gray-100" />
                    <LanguageSwitcher />
                    <template v-if="canLogin">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="btn-ghost justify-start">
                            {{ t('nav.dashboard') }}
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="btn-ghost justify-start">{{ t('nav.login') }}</Link>
                            <Link v-if="canRegister" :href="route('register')" class="btn-primary mt-2">{{ t('nav.register') }}</Link>
                        </template>
                    </template>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden pt-32 pb-20 sm:pt-40 sm:pb-32">
            <!-- Background decoration -->
            <div class="absolute inset-0 -z-10">
                <div class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-primary-100/40 blur-3xl" />
                <div class="absolute right-0 top-1/2 h-[400px] w-[400px] rounded-full bg-accent-100/30 blur-3xl" />
            </div>

            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-primary-200 bg-primary-50 px-4 py-1.5 text-sm font-medium text-primary-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        {{ t('app.tagline') }}
                    </div>

                    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                        {{ t('landing.hero.title') }}
                        <span class="gradient-text block sm:inline"> {{ t('landing.hero.titleHighlight') }}</span>
                    </h1>

                    <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-gray-500">
                        {{ t('landing.hero.subtitle') }}
                    </p>

                    <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
                        <Link v-if="canRegister" :href="route('register')" class="btn-primary !px-8 !py-3.5 !text-base">
                            {{ t('landing.hero.cta') }}
                            <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>
                        <a href="#how-it-works" class="btn-secondary !px-8 !py-3.5 !text-base">
                            {{ t('landing.hero.ctaSecondary') }}
                        </a>
                    </div>
                </div>

                <!-- Traceability Preview -->
                <div class="mx-auto mt-16 max-w-4xl">
                    <div class="rounded-2xl border border-gray-200 bg-gradient-to-b from-gray-50 to-white p-8 shadow-xl shadow-gray-200/50">
                        <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                            <div class="flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-sm font-medium text-blue-700 ring-1 ring-blue-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                {{ t('landing.traceability.values') }}
                            </div>
                            <svg class="hidden h-5 w-5 text-gray-300 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <div class="flex items-center gap-2 rounded-xl bg-violet-50 px-4 py-2.5 text-sm font-medium text-violet-700 ring-1 ring-violet-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                {{ t('landing.traceability.principles') }}
                            </div>
                            <svg class="hidden h-5 w-5 text-gray-300 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <div class="flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                {{ t('landing.traceability.goals') }}
                            </div>
                            <svg class="hidden h-5 w-5 text-gray-300 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <div class="flex items-center gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-sm font-medium text-amber-700 ring-1 ring-amber-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                {{ t('landing.traceability.vision') }}
                            </div>
                            <svg class="hidden h-5 w-5 text-gray-300 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <div class="flex items-center gap-2 rounded-xl bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 ring-1 ring-rose-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>
                                {{ t('landing.traceability.missions') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="relative py-20 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        {{ t('landing.features.sectionTitle') }}
                        <span class="gradient-text"> {{ t('landing.features.sectionTitleHighlight') }}</span>
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        {{ t('landing.features.sectionSubtitle') }}
                    </p>
                </div>

                <div class="mx-auto mt-16 grid max-w-5xl gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Values Workshop -->
                    <div class="card group">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition group-hover:bg-blue-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.features.values.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.features.values.description') }}</p>
                    </div>

                    <!-- Principles Builder -->
                    <div class="card group">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 text-violet-600 transition group-hover:bg-violet-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.features.principles.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.features.principles.description') }}</p>
                    </div>

                    <!-- Strategic Goals -->
                    <div class="card group">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 transition group-hover:bg-emerald-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.features.goals.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.features.goals.description') }}</p>
                    </div>

                    <!-- Vision Co-Creation -->
                    <div class="card group">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.features.vision.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.features.vision.description') }}</p>
                    </div>

                    <!-- Mission Generator -->
                    <div class="card group">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition group-hover:bg-rose-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.features.missions.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.features.missions.description') }}</p>
                    </div>

                    <!-- Visibility & Embedding -->
                    <div class="card group">
                        <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600 transition group-hover:bg-cyan-100">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.features.visibility.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.features.visibility.description') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works / Traceability Section -->
        <section id="how-it-works" class="relative bg-gray-50 py-20 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        {{ t('landing.traceability.sectionTitle') }}
                        <span class="gradient-text"> {{ t('landing.traceability.sectionTitleHighlight') }}</span>
                    </h2>
                    <p class="mt-4 text-lg text-gray-500">
                        {{ t('landing.traceability.sectionSubtitle') }}
                    </p>
                </div>

                <!-- Traceability flow diagram -->
                <div class="mx-auto mt-16 max-w-4xl">
                    <div class="grid gap-0">
                        <!-- Step 1 -->
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 shadow-sm">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                </div>
                                <div class="h-full w-0.5 bg-gradient-to-b from-blue-200 to-violet-200" />
                            </div>
                            <div class="pb-12">
                                <span class="text-xs font-bold uppercase tracking-wider text-blue-600">01</span>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">{{ t('landing.traceability.values') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ t('landing.features.values.description') }}</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-100 text-violet-600 shadow-sm">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                </div>
                                <div class="h-full w-0.5 bg-gradient-to-b from-violet-200 to-emerald-200" />
                            </div>
                            <div class="pb-12">
                                <span class="text-xs font-bold uppercase tracking-wider text-violet-600">02</span>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">{{ t('landing.traceability.principles') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ t('landing.features.principles.description') }}</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-600 shadow-sm">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </div>
                                <div class="h-full w-0.5 bg-gradient-to-b from-emerald-200 to-amber-200" />
                            </div>
                            <div class="pb-12">
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">03</span>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">{{ t('landing.traceability.goals') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ t('landing.features.goals.description') }}</p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600 shadow-sm">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </div>
                                <div class="h-full w-0.5 bg-gradient-to-b from-amber-200 to-rose-200" />
                            </div>
                            <div class="pb-12">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-600">04</span>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">{{ t('landing.traceability.vision') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ t('landing.features.vision.description') }}</p>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100 text-rose-600 shadow-sm">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>
                                </div>
                                <div class="h-full w-0.5 bg-gradient-to-b from-rose-200 to-cyan-200" />
                            </div>
                            <div class="pb-12">
                                <span class="text-xs font-bold uppercase tracking-wider text-rose-600">05</span>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">{{ t('landing.traceability.missions') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ t('landing.features.missions.description') }}</p>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="flex items-start gap-6">
                            <div class="flex flex-col items-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-600 shadow-sm">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" /></svg>
                                </div>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-cyan-600">06</span>
                                <h3 class="mt-1 text-xl font-bold text-gray-900">{{ t('landing.traceability.projects') }}</h3>
                                <p class="mt-1 text-sm text-gray-500">{{ t('landing.features.visibility.description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Differentiators Section -->
        <section class="py-20 sm:py-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        {{ t('landing.differentiators.sectionTitle') }}
                        <span class="gradient-text"> {{ t('landing.differentiators.sectionTitleHighlight') }}</span>
                    </h2>
                </div>

                <div class="mx-auto mt-16 grid max-w-5xl gap-8 sm:grid-cols-3">
                    <div class="text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100">
                            <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.differentiators.living.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.differentiators.living.description') }}</p>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-accent-50 to-accent-100">
                            <svg class="h-8 w-8 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.differentiators.traceable.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.differentiators.traceable.description') }}</p>
                    </div>

                    <div class="text-center">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100">
                            <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ t('landing.differentiators.collaborative.title') }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ t('landing.differentiators.collaborative.description') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-primary-600 via-primary-700 to-accent-700 py-20 sm:py-28">
            <div class="absolute inset-0 -z-0">
                <div class="absolute left-0 top-0 h-64 w-64 rounded-full bg-white/5 blur-3xl" />
                <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-accent-500/10 blur-3xl" />
            </div>
            <div class="relative mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                    {{ t('landing.cta.title') }}
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-primary-100">
                    {{ t('landing.cta.subtitle') }}
                </p>
                <div class="mt-10">
                    <Link
                        v-if="canRegister"
                        :href="route('register')"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-8 py-4 text-base font-semibold text-primary-700 shadow-xl transition-all duration-200 hover:bg-primary-50 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600"
                    >
                        {{ t('landing.cta.button') }}
                        <svg class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gray-100 bg-white py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <ApplicationLogo />
                        <p class="mt-4 max-w-xs text-sm leading-relaxed text-gray-500">
                            {{ t('landing.footer.description') }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-900">{{ t('landing.footer.product') }}</h4>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#features" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.features') }}</a></li>
                            <li><a href="#" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.pricing') }}</a></li>
                            <li><a href="#" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.roadmap') }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-900">{{ t('landing.footer.company') }}</h4>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.about') }}</a></li>
                            <li><a href="#" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.blog') }}</a></li>
                            <li><a href="#" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.careers') }}</a></li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-900">{{ t('landing.footer.legal') }}</h4>
                        <ul class="mt-4 space-y-3">
                            <li><a href="#" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.privacy') }}</a></li>
                            <li><a href="#" class="text-sm text-gray-500 transition hover:text-gray-900">{{ t('landing.footer.terms') }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="mt-12 border-t border-gray-100 pt-8 text-center">
                    <p class="text-sm text-gray-400">&copy; {{ new Date().getFullYear() }} VisionFlow. {{ t('landing.footer.rights') }}</p>
                </div>
            </div>
        </footer>
    </div>
</template>
