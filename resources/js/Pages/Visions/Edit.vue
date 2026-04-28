<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    vision: { id: string; content: string; status: string };
}>();

const form = useForm({ content: props.vision.content, status: props.vision.status });

const submit = () => { form.put(route('visions.update', props.vision.id)); };
</script>

<template>
    <Head :title="t('visions.edit.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('visions.edit.title')" :backRoute="route('visions.show', vision.id)" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('visions.create.content') }}</label>
                    <textarea class="input-field mt-1.5" rows="6" v-model="form.content" required />
                    <InputError class="mt-1.5" :message="form.errors.content" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.status') }}</label>
                    <select class="input-field mt-1.5" v-model="form.status">
                        <option value="drafting">{{ t('visions.status.drafting') }}</option>
                        <option value="reviewing">{{ t('visions.status.reviewing') }}</option>
                        <option value="approved">{{ t('visions.status.approved') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.save') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
