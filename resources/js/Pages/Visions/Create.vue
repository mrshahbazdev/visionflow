<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({ content: '' });

const submit = () => { form.post(route('visions.store')); };
</script>

<template>
    <Head :title="t('visions.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('visions.create.title')" :backRoute="route('visions.index')" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('visions.create.content') }}</label>
                    <textarea class="input-field mt-1.5" rows="6" v-model="form.content" required :placeholder="t('visions.create.contentPlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.content" />
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.create') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
