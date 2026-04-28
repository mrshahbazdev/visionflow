<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    visions: Array<{ id: string; content: string }>;
    members: Array<{ id: string; name: string; email: string }>;
}>();

const form = useForm({
    vision_id: '',
    title: '',
    description: '',
    owner_id: '',
    review_cadence: 'quarterly',
});

const submit = () => { form.post(route('missions.store')); };
</script>

<template>
    <Head :title="t('missions.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('missions.create.title')" :backRoute="route('missions.index')" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('missions.create.selectVision') }}</label>
                    <select class="input-field mt-1.5" v-model="form.vision_id" required>
                        <option value="" disabled>{{ t('missions.create.selectVision') }}</option>
                        <option v-for="v in visions" :key="v.id" :value="v.id">{{ v.content.substring(0, 80) }}...</option>
                    </select>
                    <InputError class="mt-1.5" :message="form.errors.vision_id" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.title') }}</label>
                    <input type="text" class="input-field mt-1.5" v-model="form.title" required autofocus />
                    <InputError class="mt-1.5" :message="form.errors.title" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.description') }}</label>
                    <textarea class="input-field mt-1.5" rows="3" v-model="form.description" />
                    <InputError class="mt-1.5" :message="form.errors.description" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('missions.create.owner') }}</label>
                    <select class="input-field mt-1.5" v-model="form.owner_id">
                        <option value="">{{ t('missions.create.selectOwner') }}</option>
                        <option v-for="m in members" :key="m.id" :value="m.id">{{ m.name }} ({{ m.email }})</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('missions.create.reviewCadence') }}</label>
                    <select class="input-field mt-1.5" v-model="form.review_cadence" required>
                        <option value="monthly">{{ t('missions.create.cadences.monthly') }}</option>
                        <option value="quarterly">{{ t('missions.create.cadences.quarterly') }}</option>
                        <option value="biannually">{{ t('missions.create.cadences.biannually') }}</option>
                        <option value="annually">{{ t('missions.create.cadences.annually') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.create') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
