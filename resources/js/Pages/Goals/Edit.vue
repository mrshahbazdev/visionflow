<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    goal: { id: string; title: string; description: string | null; category: string; time_horizon: string | null; values: Array<{ id: string }>; principles: Array<{ id: string }> };
    values: Array<{ id: string; title: string }>;
    principles: Array<{ id: string; statement: string }>;
}>();

const form = useForm({
    title: props.goal.title,
    description: props.goal.description ?? '',
    category: props.goal.category,
    time_horizon: props.goal.time_horizon ?? '',
    value_ids: props.goal.values.map(v => v.id),
    principle_ids: props.goal.principles.map(p => p.id),
});

const submit = () => { form.put(route('goals.update', props.goal.id)); };
</script>

<template>
    <Head :title="t('goals.edit.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('goals.edit.title')" :backRoute="route('goals.show', goal.id)" :backLabel="t('common.back')" />
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
                    <label class="block text-sm font-medium text-gray-700">{{ t('goals.create.category') }}</label>
                    <select class="input-field mt-1.5" v-model="form.category">
                        <option value="market">{{ t('goals.categories.market') }}</option>
                        <option value="impact">{{ t('goals.categories.impact') }}</option>
                        <option value="organization">{{ t('goals.categories.organization') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('goals.create.timeHorizon') }}</label>
                    <input type="text" class="input-field mt-1.5" v-model="form.time_horizon" />
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.save') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
