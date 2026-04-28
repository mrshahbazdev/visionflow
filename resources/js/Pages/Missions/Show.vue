<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    mission: {
        id: string;
        title: string;
        description: string | null;
        status: string;
        review_cadence: string;
        next_review_at: string | null;
        vision: { id: string; content: string } | null;
        owner: { name: string } | null;
        reviews: Array<{ id: string; status: string; notes: string | null; reviewed_at: string; reviewer: { name: string } }>;
        projects: Array<{ id: string; name: string; status: string }>;
    };
}>();

const deleteMission = () => {
    if (confirm(t('common.confirmDelete'))) {
        router.delete(route('missions.destroy', props.mission.id));
    }
};
</script>

<template>
    <Head :title="mission.title" />
    <AuthenticatedLayout>
        <PageHeader :title="mission.title" :backRoute="route('missions.index')" :backLabel="t('common.back')">
            <template #actions>
                <div class="flex gap-2">
                    <Link :href="route('missions.edit', mission.id)" class="btn-secondary">{{ t('common.edit') }}</Link>
                    <button @click="deleteMission" class="btn-ghost text-red-600 hover:bg-red-50">{{ t('common.delete') }}</button>
                </div>
            </template>
        </PageHeader>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card !p-6">
                    <p v-if="mission.description" class="text-gray-600 leading-relaxed">{{ mission.description }}</p>
                </div>

                <!-- Parent Vision -->
                <div v-if="mission.vision" class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ t('missions.show.vision') }}</h3>
                    <Link :href="route('visions.show', mission.vision.id)" class="block rounded-xl bg-amber-50 p-4 text-sm text-amber-700 transition hover:bg-amber-100">
                        {{ mission.vision.content }}
                    </Link>
                </div>

                <!-- Linked Projects -->
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('missions.show.projects') }}</h3>
                    <div v-if="mission.projects.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-2">
                        <Link v-for="p in mission.projects" :key="p.id" :href="route('projects.show', p.id)" class="block rounded-xl bg-cyan-50 p-3 text-sm text-cyan-700 transition hover:bg-cyan-100">
                            {{ p.name }}
                        </Link>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="card !p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ t('missions.show.reviews') }}</h3>
                    <div v-if="mission.reviews.length === 0" class="text-sm text-gray-400">{{ t('common.noResults') }}</div>
                    <div v-else class="space-y-3">
                        <div v-for="review in mission.reviews" :key="review.id" class="rounded-xl bg-gray-50 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">{{ review.reviewer.name }}</span>
                                <span :class="[
                                    review.status === 'on_track' ? 'bg-green-100 text-green-700' : review.status === 'at_risk' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700',
                                    'rounded-full px-2.5 py-0.5 text-xs font-medium'
                                ]">{{ review.status }}</span>
                            </div>
                            <p v-if="review.notes" class="mt-2 text-sm text-gray-500">{{ review.notes }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('common.status') }}</h4>
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700">{{ t(`common.${mission.status}`) }}</span>
                </div>
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('missions.show.owner') }}</h4>
                    <p class="text-gray-600">{{ mission.owner?.name ?? '-' }}</p>
                </div>
                <div class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('missions.show.reviewCadence') }}</h4>
                    <p class="text-gray-600">{{ t(`missions.create.cadences.${mission.review_cadence}`) }}</p>
                </div>
                <div v-if="mission.next_review_at" class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('missions.show.nextReview') }}</h4>
                    <p class="text-gray-600">{{ mission.next_review_at }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
