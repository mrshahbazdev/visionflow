<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    missions: Array<{ id: string; title: string }>;
}>();

const form = useForm({ mission_id: '', name: '', description: '' });

const submit = () => { form.post(route('projects.store')); };
</script>

<template>
    <Head :title="t('projects.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('projects.create.title')" :backRoute="route('projects.index')" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('projects.create.selectMission') }}</label>
                    <select class="input-field mt-1.5" v-model="form.mission_id" required>
                        <option value="" disabled>{{ t('projects.create.selectMission') }}</option>
                        <option v-for="m in missions" :key="m.id" :value="m.id">{{ m.title }}</option>
                    </select>
                    <InputError class="mt-1.5" :message="form.errors.mission_id" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('projects.create.name') }}</label>
                    <input type="text" class="input-field mt-1.5" v-model="form.name" required autofocus :placeholder="t('projects.create.namePlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.name" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.description') }}</label>
                    <textarea class="input-field mt-1.5" rows="3" v-model="form.description" />
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.create') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
