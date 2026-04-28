<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    goalsByCategory: Record<string, Array<{
        id: string;
        title: string;
        description: string | null;
        category: string;
        time_horizon: string | null;
        values: Array<{ id: string; title: string }>;
        principles: Array<{ id: string; statement: string }>;
    }>>;
}>();

const categories = ['market', 'impact', 'organization'] as const;

const categoryColor = (cat: string): string => {
    const colors: Record<string, string> = {
        market: 'bg-blue-100 text-blue-700 ring-blue-200',
        impact: 'bg-emerald-100 text-emerald-700 ring-emerald-200',
        organization: 'bg-violet-100 text-violet-700 ring-violet-200',
    };
    return colors[cat] || '';
};
</script>

<template>
    <Head :title="t('goals.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('goals.title')" :subtitle="t('goals.subtitle')" :createRoute="route('goals.create')" :createLabel="t('common.create')" />

        <div v-if="Object.keys(goalsByCategory).length === 0" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">{{ t('goals.empty') }}</p>
            <Link :href="route('goals.create')" class="btn-primary mt-4 inline-flex">{{ t('common.create') }}</Link>
        </div>

        <div v-else class="space-y-8">
            <div v-for="cat in categories" :key="cat">
                <div v-if="goalsByCategory[cat]?.length">
                    <h2 class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                        <span :class="[categoryColor(cat), 'rounded-lg px-2.5 py-1 text-xs font-medium ring-1']">{{ t(`goals.categories.${cat}`) }}</span>
                    </h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <Link v-for="goal in goalsByCategory[cat]" :key="goal.id" :href="route('goals.show', goal.id)" class="card group transition-shadow hover:shadow-md">
                            <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">{{ goal.title }}</h3>
                            <p v-if="goal.description" class="mt-2 line-clamp-2 text-sm text-gray-500">{{ goal.description }}</p>
                            <div v-if="goal.time_horizon" class="mt-3 text-xs text-gray-400">{{ t('goals.show.timeHorizon') }}: {{ goal.time_horizon }}</div>
                            <div class="mt-3 flex flex-wrap gap-1">
                                <span v-for="v in goal.values" :key="v.id" class="rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-600">{{ v.title }}</span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
