<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineProps<{
    status?: string;
}>();

const { t } = useI18n();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.forgot.title')" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ t('auth.forgot.title') }}</h1>
            <p class="mt-2 text-sm text-gray-500">{{ t('auth.forgot.subtitle') }}</p>
        </div>

        <div
            v-if="status"
            class="mb-4 rounded-lg bg-green-50 p-3 text-sm font-medium text-green-700 ring-1 ring-green-200"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">{{ t('auth.forgot.email') }}</label>
                <input
                    id="email"
                    type="email"
                    class="input-field mt-1.5"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    :placeholder="t('auth.forgot.email')"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <button
                type="submit"
                class="btn-primary w-full !py-3"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                {{ t('auth.forgot.submit') }}
            </button>

            <p class="text-center">
                <Link :href="route('login')" class="text-sm font-medium text-primary-600 transition hover:text-primary-700">
                    {{ t('auth.forgot.backToLogin') }}
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
