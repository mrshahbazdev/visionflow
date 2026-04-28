<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    value: { id: string; title: string; description: string | null; status: string };
}>();

const form = useForm({ title: props.value.title, description: props.value.description ?? '', status: props.value.status });

const submit = () => { form.put(route('values.update', props.value.id)); };
</script>

<template>
    <Head :title="t('values.edit.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('values.edit.title')" :backRoute="route('values.show', value.id)" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">{{ t('common.title') }}</label>
                    <input id="title" type="text" class="input-field mt-1.5" v-model="form.title" required />
                    <InputError class="mt-1.5" :message="form.errors.title" />
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ t('common.description') }}</label>
                    <textarea id="description" class="input-field mt-1.5" rows="4" v-model="form.description" />
                    <InputError class="mt-1.5" :message="form.errors.description" />
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">{{ t('common.status') }}</label>
                    <select id="status" class="input-field mt-1.5" v-model="form.status">
                        <option value="draft">{{ t('values.status.draft') }}</option>
                        <option value="proposed">{{ t('values.status.proposed') }}</option>
                        <option value="approved">{{ t('values.status.approved') }}</option>
                        <option value="archived">{{ t('values.status.archived') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.save') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
