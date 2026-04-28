<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    project: {
        id: string;
        name: string;
        description: string | null;
        status: string;
        mission: { id: string; title: string; vision: { id: string; content: string } | null } | null;
    };
}>();

const deleteProject = () => {
    if (confirm(t('common.confirmDelete'))) {
        router.delete(route('projects.destroy', props.project.id));
    }
};
</script>

<template>
    <Head :title="project.name" />
    <AuthenticatedLayout>
        <PageHeader :title="project.name" :backRoute="route('projects.index')" :backLabel="t('common.back')">
            <template #actions>
                <button @click="deleteProject" class="btn-ghost text-red-600 hover:bg-red-50">{{ t('common.delete') }}</button>
            </template>
        </PageHeader>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="card !p-6">
                    <p v-if="project.description" class="text-gray-600 leading-relaxed">{{ project.description }}</p>
                </div>
            </div>
            <div class="space-y-6">
                <div v-if="project.mission" class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('projects.show.mission') }}</h4>
                    <Link :href="route('missions.show', project.mission.id)" class="text-sm text-primary-600 hover:underline">{{ project.mission.title }}</Link>
                </div>
                <div v-if="project.mission?.vision" class="card !p-5">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ t('projects.show.vision') }}</h4>
                    <Link :href="route('visions.show', project.mission.vision.id)" class="text-sm text-amber-600 hover:underline line-clamp-3">{{ project.mission.vision.content }}</Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
