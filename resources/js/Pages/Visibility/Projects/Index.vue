<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    projects: Array<{
        id: string;
        name: string;
        description: string | null;
        status: string;
        mission: { id: string; title: string; vision: { content: string } | null } | null;
    }>;
}>();
</script>

<template>
    <Head :title="t('projects.title')" />
    <AuthenticatedLayout>
        <PageHeader :title="t('projects.title')" :subtitle="t('projects.subtitle')" :createRoute="route('projects.create')" :createLabel="t('common.create')" />

        <div v-if="projects.length === 0" class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center">
            <p class="text-sm text-gray-500">{{ t('projects.empty') }}</p>
            <Link :href="route('projects.create')" class="btn-primary mt-4 inline-flex">{{ t('common.create') }}</Link>
        </div>

        <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Link v-for="project in projects" :key="project.id" :href="route('projects.show', project.id)" class="card group transition-shadow hover:shadow-md">
                <h3 class="font-semibold text-gray-900 group-hover:text-primary-600">{{ project.name }}</h3>
                <p v-if="project.description" class="mt-2 line-clamp-2 text-sm text-gray-500">{{ project.description }}</p>
                <div v-if="project.mission" class="mt-3 text-xs text-gray-400">
                    {{ t('projects.show.mission') }}: {{ project.mission.title }}
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
