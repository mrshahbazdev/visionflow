<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const page = usePage();

const props = defineProps<{
    stats?: Record<string, number>;
    hasOrganization?: boolean;
}>();

const statItems = [
    { key: 'values', color: 'blue', icon: 'heart' },
    { key: 'principles', color: 'violet', icon: 'shield' },
    { key: 'goals', color: 'emerald', icon: 'bolt' },
    { key: 'visions', color: 'amber', icon: 'eye' },
    { key: 'missions', color: 'rose', icon: 'fire' },
    { key: 'projects', color: 'cyan', icon: 'screen' },
    { key: 'decisions', color: 'gray', icon: 'document' },
];

const quickActions = [
    { label: 'values.title', route: 'values.create', color: 'blue' },
    { label: 'principles.title', route: 'principles.create', color: 'violet' },
    { label: 'goals.title', route: 'goals.create', color: 'emerald' },
    { label: 'visions.title', route: 'visions.create', color: 'amber' },
    { label: 'missions.title', route: 'missions.create', color: 'rose' },
    { label: 'decisions.title', route: 'decisions.create', color: 'cyan' },
];
</script>

<template>
    <Head :title="t('dashboard.title')" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <!-- Welcome -->
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    {{ t('dashboard.welcome') }}, {{ $page.props.auth.user.name }}
                </h1>
                <p class="mt-1 text-sm text-gray-500">{{ t('dashboard.overview') }}</p>
            </div>

            <!-- No Organization CTA -->
            <div v-if="!hasOrganization" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ t('dashboard.noOrg') }}</h3>
                <div class="mt-6">
                    <Link :href="route('organization.create')" class="btn-primary">
                        {{ t('dashboard.createOrg') }}
                    </Link>
                </div>
            </div>

            <!-- Stats Grid -->
            <div v-else>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div v-for="item in statItems" :key="item.key" class="card !p-5">
                        <div class="flex items-center gap-4">
                            <div :class="[
                                'flex h-12 w-12 items-center justify-center rounded-xl',
                                item.color === 'blue' ? 'bg-blue-50 text-blue-600' : '',
                                item.color === 'violet' ? 'bg-violet-50 text-violet-600' : '',
                                item.color === 'emerald' ? 'bg-emerald-50 text-emerald-600' : '',
                                item.color === 'amber' ? 'bg-amber-50 text-amber-600' : '',
                                item.color === 'rose' ? 'bg-rose-50 text-rose-600' : '',
                                item.color === 'cyan' ? 'bg-cyan-50 text-cyan-600' : '',
                                item.color === 'gray' ? 'bg-gray-50 text-gray-600' : '',
                            ]">
                                <svg v-if="item.icon === 'heart'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                <svg v-else-if="item.icon === 'shield'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                <svg v-else-if="item.icon === 'bolt'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                <svg v-else-if="item.icon === 'eye'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg v-else-if="item.icon === 'fire'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /></svg>
                                <svg v-else-if="item.icon === 'screen'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" /></svg>
                                <svg v-else-if="item.icon === 'document'" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" /></svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ stats?.[item.key] ?? 0 }}</p>
                                <p class="text-sm text-gray-500">{{ t(`dashboard.stats.${item.key}`) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-900">{{ t('dashboard.quickActions') }}</h2>
                    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        <Link
                            v-for="action in quickActions"
                            :key="action.route"
                            :href="route(action.route)"
                            class="card flex items-center gap-3 !p-4 transition-shadow hover:shadow-md"
                        >
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            <span class="text-sm font-medium text-gray-700">{{ t(action.label) }}</span>
                        </Link>
                    </div>
                </div>

                <!-- Traceability Chain -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold text-gray-900">{{ t('dashboard.traceabilityChain') }}</h2>
                    <div class="mt-4 card !p-6">
                        <div class="flex flex-wrap items-center justify-center gap-3">
                            <Link :href="route('values.index')" class="flex items-center gap-2 rounded-xl bg-blue-50 px-4 py-2.5 text-sm font-medium text-blue-700 ring-1 ring-blue-200 transition hover:bg-blue-100">
                                {{ t('landing.traceability.values') }}
                                <span class="ml-1 rounded-full bg-blue-200 px-2 py-0.5 text-xs">{{ stats?.values ?? 0 }}</span>
                            </Link>
                            <svg class="h-5 w-5 text-gray-300 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <Link :href="route('principles.index')" class="flex items-center gap-2 rounded-xl bg-violet-50 px-4 py-2.5 text-sm font-medium text-violet-700 ring-1 ring-violet-200 transition hover:bg-violet-100">
                                {{ t('landing.traceability.principles') }}
                                <span class="ml-1 rounded-full bg-violet-200 px-2 py-0.5 text-xs">{{ stats?.principles ?? 0 }}</span>
                            </Link>
                            <svg class="h-5 w-5 text-gray-300 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <Link :href="route('goals.index')" class="flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700 ring-1 ring-emerald-200 transition hover:bg-emerald-100">
                                {{ t('landing.traceability.goals') }}
                                <span class="ml-1 rounded-full bg-emerald-200 px-2 py-0.5 text-xs">{{ stats?.goals ?? 0 }}</span>
                            </Link>
                            <svg class="h-5 w-5 text-gray-300 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <Link :href="route('visions.index')" class="flex items-center gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-sm font-medium text-amber-700 ring-1 ring-amber-200 transition hover:bg-amber-100">
                                {{ t('landing.traceability.vision') }}
                                <span class="ml-1 rounded-full bg-amber-200 px-2 py-0.5 text-xs">{{ stats?.visions ?? 0 }}</span>
                            </Link>
                            <svg class="h-5 w-5 text-gray-300 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                            <Link :href="route('missions.index')" class="flex items-center gap-2 rounded-xl bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 ring-1 ring-rose-200 transition hover:bg-rose-100">
                                {{ t('landing.traceability.missions') }}
                                <span class="ml-1 rounded-full bg-rose-200 px-2 py-0.5 text-xs">{{ stats?.missions ?? 0 }}</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
