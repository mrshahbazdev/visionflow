<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    principle: {
        id: string;
        statement: string;
        status: string;
        alignment_score: number | null;
        value: { id: string; title: string } | null;
        consensus_votes: Array<{ id: string; vote: string; comment: string | null; user: { name: string } }>;
    };
}>();

const deletePrinciple = () => {
    if (confirm(t('common.confirmDelete'))) {
        router.delete(route('principles.destroy', props.principle.id));
    }
};
</script>

<template>
    <Head :title="principle.statement.substring(0, 50)" />
    <AuthenticatedLayout>
        <PageHeader :title="principle.statement" :backRoute="route('principles.index')" :backLabel="t('common.back')">
            <template #actions>
                <div class="flex gap-2">
                    <Link :href="route('principles.edit', principle.id)" class="btn-secondary">{{ t('common.edit') }}</Link>
                    <button @click="deletePrinciple" class="btn-ghost text-red-600 hover:bg-red-50">{{ t('common.delete') }}</button>
                </div>
            </template>
        </PageHeader>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('principles.show.consensus') }}</h3>
                    <div v-if="principle.consensus_votes.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-3">
                        <div v-for="vote in principle.consensus_votes" :key="vote.id" class="rounded-xl bg-gray-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">{{ vote.user.name }}</span>
                                <span :class="[
                                    vote.vote === 'agree' ? 'bg-green-100 text-green-700' : vote.vote === 'disagree' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600',
                                    'rounded-full px-2.5 py-0.5 text-xs font-medium'
                                ]">{{ vote.vote }}</span>
                            </div>
                            <p v-if="vote.comment" class="mt-2 text-sm text-gray-500">{{ vote.comment }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('principles.show.linkedValue') }}</h4>
                    <Link v-if="principle.value" :href="route('values.show', principle.value.id)" class="text-sm text-primary-600 hover:underline">{{ principle.value.title }}</Link>
                    <span v-else class="text-sm text-gray-400">-</span>
                </div>
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('principles.show.alignmentScore') }}</h4>
                    <p class="text-2xl font-bold text-gray-900">{{ principle.alignment_score ?? '-' }}</p>
                </div>
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('common.status') }}</h4>
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">{{ t(`common.${principle.status}`) }}</span>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
