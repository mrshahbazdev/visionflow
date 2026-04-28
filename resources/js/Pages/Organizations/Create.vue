<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({
    name: '',
    description: '',
});

const submit = () => {
    form.post(route('organization.store'));
};
</script>

<template>
    <Head :title="t('organization.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('organization.create.title')" :subtitle="t('organization.create.subtitle')" />

            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ t('organization.create.name') }}</label>
                    <input id="name" type="text" class="input-field mt-1.5" v-model="form.name" required autofocus :placeholder="t('organization.create.namePlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.name" />
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">{{ t('organization.create.description') }}</label>
                    <textarea id="description" class="input-field mt-1.5" rows="3" v-model="form.description" :placeholder="t('organization.create.descriptionPlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.description" />
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('organization.create.submit') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
