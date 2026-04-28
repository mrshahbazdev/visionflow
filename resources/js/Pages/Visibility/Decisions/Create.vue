<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    values: Array<{ id: string; title: string }>;
    missions: Array<{ id: string; title: string }>;
}>();

const form = useForm({
    title: '',
    description: '',
    decision: '',
    supporting_value_id: '',
    supporting_mission_id: '',
});

const submit = () => { form.post(route('decisions.store')); };
</script>

<template>
    <Head :title="t('decisions.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('decisions.create.title')" :backRoute="route('decisions.index')" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.title') }}</label>
                    <input type="text" class="input-field mt-1.5" v-model="form.title" required autofocus />
                    <InputError class="mt-1.5" :message="form.errors.title" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('common.description') }}</label>
                    <textarea class="input-field mt-1.5" rows="2" v-model="form.description" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('decisions.create.decision') }}</label>
                    <textarea class="input-field mt-1.5" rows="4" v-model="form.decision" required :placeholder="t('decisions.create.decisionPlaceholder')" />
                    <InputError class="mt-1.5" :message="form.errors.decision" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('decisions.create.supportingValue') }}</label>
                    <select class="input-field mt-1.5" v-model="form.supporting_value_id">
                        <option value="">{{ t('decisions.create.selectOptional') }}</option>
                        <option v-for="v in values" :key="v.id" :value="v.id">{{ v.title }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('decisions.create.supportingMission') }}</label>
                    <select class="input-field mt-1.5" v-model="form.supporting_mission_id">
                        <option value="">{{ t('decisions.create.selectOptional') }}</option>
                        <option v-for="m in missions" :key="m.id" :value="m.id">{{ m.title }}</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.submit') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
