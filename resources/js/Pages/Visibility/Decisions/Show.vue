<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    decision: {
        id: string;
        title: string;
        description: string | null;
        decision: string;
        created_at: string;
        user: { name: string };
        supporting_value: { id: string; title: string } | null;
        supporting_mission: { id: string; title: string } | null;
    };
}>();
</script>

<template>
    <Head :title="decision.title" />
    <AuthenticatedLayout>
        <PageHeader :title="decision.title" :backRoute="route('decisions.index')" :backLabel="t('common.back')" />

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card !p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ t('decisions.show.decision') }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ decision.decision }}</p>
                </div>
                <div v-if="decision.description" class="card !p-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">{{ t('common.description') }}</h3>
                    <p class="text-gray-500">{{ decision.description }}</p>
                </div>
            </div>
            <div class="space-y-6">
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('decisions.show.madeBy') }}</h4>
                    <p class="text-gray-600">{{ decision.user.name }}</p>
                </div>
                <div v-if="decision.supporting_value" class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('decisions.show.supportingValue') }}</h4>
                    <Link :href="route('values.show', decision.supporting_value.id)" class="text-sm text-blue-600 hover:underline">{{ decision.supporting_value.title }}</Link>
                </div>
                <div v-if="decision.supporting_mission" class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('decisions.show.supportingMission') }}</h4>
                    <Link :href="route('missions.show', decision.supporting_mission.id)" class="text-sm text-rose-600 hover:underline">{{ decision.supporting_mission.title }}</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
