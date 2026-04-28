<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    principles: Array<{
        id: string;
        statement: string;
        status: string;
        alignment_score: number | null;
        value: { id: string; title: string } | null;
        consensus_votes_count: number;
    }>;
}>();
</script>

<template>
    <Head :title="t('principles.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('principles.title')" :subtitle="t('principles.subtitle')" :createRoute="route('principles.create')" :createLabel="t('common.create')" />

        <div v-if="principles.length === 0" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">{{ t('principles.empty') }}</p>
            <Link :href="route('principles.create')" class="btn-primary mt-4 inline-flex">{{ t('common.create') }}</Link>
        </div>

        <div v-else class="space-y-3">
            <Link v-for="principle in principles" :key="principle.id" :href="route('principles.show', principle.id)" class="card group block transition-shadow hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900 group-hover:text-primary-600">{{ principle.statement }}</p>
                        <div class="mt-2 flex items-center gap-3 text-xs text-gray-400">
                            <span v-if="principle.value" class="rounded-full bg-blue-50 px-2 py-0.5 text-blue-600">{{ principle.value.title }}</span>
                            <span>{{ principle.consensus_votes_count }} {{ t('principles.consensusVotes') }}</span>
                        </div>
                    </div>
                    <span :class="[
                        principle.status === 'approved' ? 'bg-green-100 text-green-700' : principle.status === 'proposed' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700',
                        'rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap'
                    ]">{{ t(`common.${principle.status}`) }}</span>
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
