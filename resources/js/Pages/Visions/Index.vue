<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    visions: Array<{
        id: string;
        content: string;
        status: string;
        is_current: boolean;
        version: number;
        drafts_count: number;
        approvals_count: number;
        missions_count: number;
    }>;
}>();
</script>

<template>
    <Head :title="t('visions.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('visions.title')" :subtitle="t('visions.subtitle')" :createRoute="route('visions.create')" :createLabel="t('common.create')" />

        <div v-if="visions.length === 0" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">{{ t('visions.empty') }}</p>
            <Link :href="route('visions.create')" class="btn-primary mt-4 inline-flex">{{ t('common.create') }}</Link>
        </div>

        <div v-else class="space-y-4">
            <Link v-for="vision in visions" :key="vision.id" :href="route('visions.show', vision.id)" class="card group block transition-shadow hover:shadow-md">
                <div class="flex items-start gap-4">
                    <div v-if="vision.is_current" class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span v-if="vision.is_current" class="text-xs font-medium text-amber-600">{{ t('visions.current') }}</span>
                            <span :class="[
                                vision.status === 'approved' ? 'bg-green-100 text-green-700' : vision.status === 'reviewing' ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-700',
                                'rounded-full px-2.5 py-0.5 text-xs font-medium'
                            ]">{{ t(`visions.status.${vision.status}`) }}</span>
                        </div>
                        <p class="mt-2 text-gray-700 line-clamp-3 group-hover:text-primary-600">{{ vision.content }}</p>
                        <div class="mt-3 flex gap-4 text-xs text-gray-400">
                            <span>{{ vision.drafts_count }} {{ t('visions.drafts') }}</span>
                            <span>{{ vision.approvals_count }} {{ t('visions.approvals') }}</span>
                            <span>{{ vision.missions_count }} {{ t('visions.missions') }}</span>
                        </div>
                    </div>
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
