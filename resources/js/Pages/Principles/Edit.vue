<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    principle: { id: string; statement: string; status: string; value_id: string | null };
    values: Array<{ id: string; title: string }>;
}>();

const form = useForm({ value_id: props.principle.value_id ?? '', statement: props.principle.statement, status: props.principle.status });

const submit = () => { form.put(route('principles.update', props.principle.id)); };
</script>

<template>
    <Head :title="t('principles.edit.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('principles.edit.title')" :backRoute="route('principles.show', principle.id)" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('principles.create.selectValue') }}</label>
                    <select class="input-field mt-1.5" v-model="form.value_id" required>
                        <option v-for="v in values" :key="v.id" :value="v.id">{{ v.title }}</option>
                    </select>
                    <InputError class="mt-1.5" :message="form.errors.value_id" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('principles.create.statement') }}</label>
                    <textarea class="input-field mt-1.5" rows="4" v-model="form.statement" required />
                    <InputError class="mt-1.5" :message="form.errors.statement" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.status') }}</label>
                    <select class="input-field mt-1.5" v-model="form.status">
                        <option value="draft">{{ t('common.draft') }}</option>
                        <option value="proposed">{{ t('common.proposed') }}</option>
                        <option value="approved">{{ t('common.approved') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.save') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
