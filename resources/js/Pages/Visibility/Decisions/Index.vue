<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    decisions: Array<{
        id: string;
        title: string;
        description: string | null;
        decision: string;
        created_at: string;
        user: { name: string };
        supporting_value: { title: string } | null;
        supporting_mission: { title: string } | null;
    }>;
}>();
</script>

<template>
    <Head :title="t('decisions.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('decisions.title')" :subtitle="t('decisions.subtitle')" :createRoute="route('decisions.create')" :createLabel="t('common.create')" />

        <div v-if="decisions.length === 0" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">{{ t('decisions.empty') }}</p>
            <Link :href="route('decisions.create')" class="btn-primary mt-4 inline-flex">{{ t('common.create') }}</Link>
        </div>

        <div v-else class="space-y-3">
            <Link v-for="d in decisions" :key="d.id" :href="route('decisions.show', d.id)" class="card group block transition-shadow hover:shadow-md">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">{{ d.title }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-gray-500">{{ d.decision }}</p>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-gray-400">
                            <span>{{ d.user.name }}</span>
                            <span v-if="d.supporting_value" class="rounded-full bg-blue-50 px-2 py-0.5 text-blue-600">{{ d.supporting_value.title }}</span>
                            <span v-if="d.supporting_mission" class="rounded-full bg-rose-50 px-2 py-0.5 text-rose-600">{{ d.supporting_mission.title }}</span>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
