<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/PageHeader.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';

const { t } = useI18n();

defineProps<{
    values: Array<{ id: string; title: string }>;
    principles: Array<{ id: string; statement: string }>;
}>();

const form = useForm({
    title: '',
    description: '',
    category: 'market',
    time_horizon: '',
    value_ids: [] as string[],
    principle_ids: [] as string[],
});

const submit = () => { form.post(route('goals.store')); };
</script>

<template>
    <Head :title="t('goals.create.title')" />
    <AuthenticatedLayout>
        <div class="mx-auto max-w-xl">
            <PageHeader :title="t('goals.create.title')" :backRoute="route('goals.index')" :backLabel="t('common.back')" />
            <form @submit.prevent="submit" class="card space-y-5 !p-6">
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
                    <label class="block text-sm font-medium text-gray-700">{{ t('goals.create.category') }}</label>
                    <select class="input-field mt-1.5" v-model="form.category">
                        <option value="market">{{ t('goals.categories.market') }}</option>
                        <option value="impact">{{ t('goals.categories.impact') }}</option>
                        <option value="organization">{{ t('goals.categories.organization') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">{{ t('goals.create.timeHorizon') }}</label>
                    <input type="text" class="input-field mt-1.5" v-model="form.time_horizon" :placeholder="t('goals.create.timeHorizonPlaceholder')" />
                </div>
                <div v-if="values.length > 0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('goals.create.linkValues') }}</label>
                    <div class="space-y-2">
                        <label v-for="v in values" :key="v.id" class="flex items-center gap-2 text-sm">
                            <input type="checkbox" :value="v.id" v-model="form.value_ids" class="rounded border-gray-300 text-primary-600" />
                            {{ v.title }}
                        </label>
                    </div>
                </div>
                <div v-if="principles.length > 0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ t('goals.create.linkPrinciples') }}</label>
                    <div class="space-y-2">
                        <label v-for="p in principles" :key="p.id" class="flex items-center gap-2 text-sm">
                            <input type="checkbox" :value="p.id" v-model="form.principle_ids" class="rounded border-gray-300 text-primary-600" />
                            {{ p.statement.substring(0, 80) }}...
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="form.processing">{{ t('common.create') }}</button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
