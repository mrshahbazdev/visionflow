<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    mission: { id: string; title: string; description: string | null; status: string; vision_id: string; owner_id: string | null; review_cadence: string };
    visions: Array<{ id: string; content: string }>;
    members: Array<{ id: string; name: string; email: string }>;
}>();

const form = useForm({
    vision_id: props.mission.vision_id,
    title: props.mission.title,
    description: props.mission.description ?? '',
    owner_id: props.mission.owner_id ?? '',
    status: props.mission.status,
    review_cadence: props.mission.review_cadence,
});

const submit = () => { form.put(route('missions.update', props.mission.id)); };
</script>

<template>
    <Head :title="t('missions.edit.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('missions.edit.title')" :backRoute="route('missions.show', mission.id)" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.title') }}</label>
                    <input type="text" class="input-field mt-1.5" v-model="form.title" required />
                    <InputError class="mt-1.5" :message="form.errors.title" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.description') }}</label>
                    <textarea class="input-field mt-1.5" rows="3" v-model="form.description" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.status') }}</label>
                    <select class="input-field mt-1.5" v-model="form.status">
                        <option value="active">{{ t('common.active') }}</option>
                        <option value="paused">{{ t('common.paused') }}</option>
                        <option value="completed">{{ t('common.completed') }}</option>
                        <option value="archived">{{ t('common.archived') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('missions.create.reviewCadence') }}</label>
                    <select class="input-field mt-1.5" v-model="form.review_cadence">
                        <option value="monthly">{{ t('missions.create.cadences.monthly') }}</option>
                        <option value="quarterly">{{ t('missions.create.cadences.quarterly') }}</option>
                        <option value="biannually">{{ t('missions.create.cadences.biannually') }}</option>
                        <option value="annually">{{ t('missions.create.cadences.annually') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.save') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
