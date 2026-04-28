<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({ title: '', description: '' });

const submit = () => { form.post(route('values.store')); };
</script>

<template>
    <Head :title="t('values.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('values.create.title')" :backRoute="route('values.index')" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">{{ t('common.title') }}</label>
                    <input id="title" type="text" class="input-field mt-1.5" v-model="form.title" required autofocus :placeholder="t('values.create.titlePlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.title" />
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ t('common.description') }}</label>
                    <textarea id="description" class="input-field mt-1.5" rows="4" v-model="form.description" :placeholder="t('values.create.descriptionPlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.description" />
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1" :disabled="form.processing">{{ t('common.create') }}</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
