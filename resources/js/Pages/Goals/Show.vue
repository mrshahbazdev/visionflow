<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    goal: {
        id: string;
        title: string;
        description: string | null;
        category: string;
        time_horizon: string | null;
        values: Array<{ id: string; title: string }>;
        principles: Array<{ id: string; statement: string }>;
    };
}>();

const deleteGoal = () => {
    if (confirm(t('common.confirmDelete'))) {
        router.delete(route('goals.destroy', props.goal.id));
    }
};
</script>

<template>
    <Head :title="goal.title" />
    <AuthenticatedLayout>
        <PageHeader :title="goal.title" :backRoute="route('goals.index')" :backLabel="t('common.back')">
            <template #actions>
                <div class="flex gap-2">
                    <Link :href="route('goals.edit', goal.id)" class="btn-secondary">{{ t('common.edit') }}</Link>
                    <button @click="deleteGoal" class="btn-ghost text-red-600 hover:bg-red-50">{{ t('common.delete') }}</button>
                </div>
            </template>
        </PageHeader>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card !p-6">
                    <p v-if="goal.description" class="text-gray-600 leading-relaxed">{{ goal.description }}</p>
                </div>
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('goals.show.linkedValues') }}</h3>
                    <div v-if="goal.values.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="flex flex-wrap gap-2">
                        <Link v-for="v in goal.values" :key="v.id" :href="route('values.show', v.id)" class="rounded-xl bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100">{{ v.title }}</Link>
                    </div>
                </div>
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('goals.show.linkedPrinciples') }}</h3>
                    <div v-if="goal.principles.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-2">
                        <Link v-for="p in goal.principles" :key="p.id" :href="route('principles.show', p.id)" class="block rounded-xl bg-violet-50 p-3 text-sm text-violet-700 transition hover:bg-violet-100">{{ p.statement }}</Link>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('goals.create.category') }}</h4>
                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-700">{{ t(`goals.categories.${goal.category}`) }}</span>
                </div>
                <div v-if="goal.time_horizon" class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('goals.show.timeHorizon') }}</h4>
                    <p class="text-gray-600">{{ goal.time_horizon }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
