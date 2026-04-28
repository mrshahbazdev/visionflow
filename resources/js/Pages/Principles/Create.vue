<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    values: Array<{ id: string; title: string }>;
}>();

const form = useForm({ value_id: '', statement: '', template_key: '' });

const submit = () => { form.post(route('principles.store')); };
</script>

<template>
    <Head :title="t('principles.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('principles.create.title')" :backRoute="route('principles.index')" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label for="value_id" class="block text-sm font-medium text-gray-700">{{ t('principles.create.selectValue') }}</label>
                    <select id="value_id" class="input-field mt-1.5" v-model="form.value_id" required>
                        <option value="" disabled>{{ t('principles.create.selectValue') }}</option>
                        <option v-for="v in values" :key="v.id" :value="v.id">{{ v.title }}</option>
                    </select>
                    <InputError class="mt-1.5" :message="form.errors.value_id" />
                </div>
                <div>
                    <label for="statement" class="block text-sm font-medium text-gray-700">{{ t('principles.create.statement') }}</label>
                    <textarea id="statement" class="input-field mt-1.5" rows="4" v-model="form.statement" required :placeholder="t('principles.create.statementPlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.statement" />
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.create') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
