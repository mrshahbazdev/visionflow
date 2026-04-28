<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    missions: Array<{
        id: string;
        title: string;
        description: string | null;
        status: string;
        review_cadence: string;
        vision: { id: string; content: string } | null;
        owner: { name: string } | null;
        reviews_count: number;
        projects_count: number;
    }>;
}>();

const statusColor = (status: string): string => {
    const colors: Record<string, string> = {
        active: 'bg-green-100 text-green-700',
        paused: 'bg-amber-100 text-amber-700',
        completed: 'bg-blue-100 text-blue-700',
        archived: 'bg-gray-100 text-gray-700',
    };
    return colors[status] || colors.active;
};
</script>

<template>
    <Head :title="t('missions.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('missions.title')" :subtitle="t('missions.subtitle')" :createRoute="route('missions.create')" :createLabel="t('common.create')" />

        <div v-if="missions.length === 0" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">{{ t('missions.empty') }}</p>
            <Link :href="route('missions.create')" class="btn-primary mt-4 inline-flex">{{ t('common.create') }}</Link>
        </div>

        <div v-else class="grid gap-4 sm:grid-cols-2">
            <Link v-for="mission in missions" :key="mission.id" :href="route('missions.show', mission.id)" class="card group transition-shadow hover:shadow-md">
                <div class="flex items-start justify-between">
                    <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">{{ mission.title }}</h3>
                    <span :class="[statusColor(mission.status), 'rounded-full px-2.5 py-0.5 text-xs font-medium']">{{ t(`common.${mission.status}`) }}</span>
                </div>
                <p v-if="mission.description" class="mt-2 line-clamp-2 text-sm text-gray-500">{{ mission.description }}</p>
                <div class="mt-3 text-xs text-gray-400">
                    <span v-if="mission.owner">{{ t('missions.show.owner') }}: {{ mission.owner.name }}</span>
                </div>
                <div class="mt-2 flex gap-4 text-xs text-gray-400">
                    <span>{{ mission.reviews_count }} {{ t('missions.reviews') }}</span>
                    <span>{{ mission.projects_count }} {{ t('missions.projects') }}</span>
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
