<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    vision: {
        id: string;
        content: string;
        status: string;
        is_current: boolean;
        version: number;
        drafts: Array<{ id: string; content: string; author: { name: string } }>;
        approvals: Array<{ id: string; decision: string; comment: string | null; user: { name: string } }>;
        missions: Array<{ id: string; title: string; status: string }>;
    };
}>();

const setCurrent = () => { router.post(route('visions.set-current', props.vision.id)); };
const deleteVision = () => {
    if (confirm(t('common.confirmDelete'))) {
        router.delete(route('visions.destroy', props.vision.id));
    }
};
</script>

<template>
    <Head :title="t('visions.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('visions.title')" :backRoute="route('visions.index')" :backLabel="t('common.back')">
            <template #actions>
                <div class="flex gap-2">
                    <button v-if="!vision.is_current" @click="setCurrent" class="btn-secondary">{{ t('visions.show.setCurrent') }}</button>
                    <Link :href="route('visions.edit', vision.id)" class="btn-secondary">{{ t('common.edit') }}</Link>
                    <button @click="deleteVision" class="btn-ghost text-red-600 hover:bg-red-50">{{ t('common.delete') }}</button>
                </div>
            </template>
        </PageHeader>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card !p-6">
                    <div v-if="vision.is_current" class="mb-4 inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-sm font-medium text-amber-700">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        {{ t('visions.current') }}
                    </div>
                    <p class="text-lg text-gray-700 leading-relaxed">{{ vision.content }}</p>
                </div>

                <!-- Missions -->
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('visions.show.missions') }}</h3>
                    <div v-if="vision.missions.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-2">
                        <Link v-for="m in vision.missions" :key="m.id" :href="route('missions.show', m.id)" class="block rounded-xl bg-rose-50 p-3 text-sm text-rose-700 transition hover:bg-rose-100">
                            {{ m.title }}
                        </Link>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('common.status') }}</h4>
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">{{ t(`visions.status.${vision.status}`) }}</span>
                    <p class="mt-3 text-xs text-gray-400">Version {{ vision.version }}</p>
                </div>
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('visions.show.approvals') }} ({{ vision.approvals.length }})</h4>
                    <div v-if="vision.approvals.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-2">
                        <div v-for="a in vision.approvals" :key="a.id" class="rounded-lg bg-gray-50 p-2.5 text-sm">
                            <span class="font-medium">{{ a.user.name }}</span> — {{ a.decision }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
