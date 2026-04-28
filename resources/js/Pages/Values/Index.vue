<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    values: Array<{
        id: string;
        title: string;
        description: string | null;
        status: string;
        cards_count: number;
        votes_count: number;
        principles_count: number;
    }>;
}>();

const statusColor = (status: string): string => {
    const colors: Record<string, string> = {
        draft: 'bg-gray-100 text-gray-700',
        proposed: 'bg-amber-100 text-amber-700',
        approved: 'bg-green-100 text-green-700',
        archived: 'bg-red-100 text-red-700',
    };
    return colors[status] || colors.draft;
};
</script>

<template>
    <Head :title="t('values.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('values.title')" :subtitle="t('values.subtitle')" :createRoute="route('values.create')" :createLabel="t('common.create')" />

        <div v-if="values.length === 0" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
            <p class="mt-4 text-sm text-gray-500">{{ t('values.empty') }}</p>
            <Link :href="route('values.create')" class="btn-primary mt-4 inline-flex">{{ t('common.create') }}</Link>
        </div>

        <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="value in values" :key="value.id" :href="route('values.show', value.id)" class="card group transition-shadow hover:shadow-md">
                <div class="flex items-start justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600">{{ value.title }}</h3>
                    <span :class="[statusColor(value.status), 'rounded-full px-2.5 py-0.5 text-xs font-medium']">
                        {{ t(`values.status.${value.status}`) }}
                    </span>
                </div>
                <p v-if="value.description" class="mt-2 line-clamp-2 text-sm text-gray-500">{{ value.description }}</p>
                <div class="mt-4 flex gap-4 text-xs text-gray-400">
                    <span>{{ value.cards_count }} {{ t('values.cards') }}</span>
                    <span>{{ value.votes_count }} {{ t('values.votes') }}</span>
                    <span>{{ value.principles_count }} {{ t('values.linkedPrinciples') }}</span>
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
