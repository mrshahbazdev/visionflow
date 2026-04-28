<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    value: {
        id: string;
        title: string;
        description: string | null;
        status: string;
        version: number;
        cards: Array<{ id: string; content: string; is_anonymous: boolean; author: { name: string } | null }>;
        votes: Array<{ id: string; score: number; user: { name: string } }>;
        principles: Array<{ id: string; statement: string; status: string }>;
        versions: Array<{ id: string; data: Record<string, unknown>; changed_by_user: { name: string }; created_at: string }>;
    };
}>();

const deleteValue = () => {
    if (confirm(t('common.confirmDelete'))) {
        router.delete(route('values.destroy', props.value.id));
    }
};
</script>

<template>
    <Head :title="value.title" />
    <AuthenticatedLayout>
        <PageHeader :title="value.title" :backRoute="route('values.index')" :backLabel="t('common.back')">
            <template #actions>
                <div class="flex gap-2">
                    <Link :href="route('values.edit', value.id)" class="btn-secondary">{{ t('common.edit') }}</Link>
                    <button @click="deleteValue" class="btn-ghost text-red-600 hover:bg-red-50">{{ t('common.delete') }}</button>
                </div>
            </template>
        </PageHeader>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card !p-6">
                    <p v-if="value.description" class="text-gray-600 leading-relaxed">{{ value.description }}</p>
                    <p v-else class="text-gray-400 italic">{{ t('common.noResults') }}</p>
                </div>

                <!-- Value Cards -->
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('values.show.cards') }}</h3>
                    <div v-if="value.cards.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-3">
                        <div v-for="card in value.cards" :key="card.id" class="rounded-xl bg-gray-50 p-4">
                            <p class="text-sm text-gray-700">{{ card.content }}</p>
                            <p class="mt-2 text-xs text-gray-400">{{ card.is_anonymous ? 'Anonymous' : card.author?.name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Linked Principles -->
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('values.show.principles') }}</h3>
                    <div v-if="value.principles.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-2">
                        <Link v-for="p in value.principles" :key="p.id" :href="route('principles.show', p.id)" class="block rounded-xl bg-violet-50 p-3 text-sm text-violet-700 transition hover:bg-violet-100">
                            {{ p.statement }}
                        </Link>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('common.status') }}</h4>
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">{{ t(`values.status.${value.status}`) }}</span>
                    <p class="mt-3 text-xs text-gray-400">Version {{ value.version }}</p>
                </div>

                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('values.show.votes') }} ({{ value.votes.length }})</h4>
                    <div v-if="value.votes.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-2">
                        <div v-for="vote in value.votes" :key="vote.id" class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">{{ vote.user.name }}</span>
                            <span class="font-medium text-gray-900">{{ vote.score }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
