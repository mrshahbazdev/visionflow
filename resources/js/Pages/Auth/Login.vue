<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const { t } = useI18n();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.login.title')" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ t('auth.login.title') }}</h1>
            <p class="mt-2 text-sm text-gray-500">{{ t('auth.login.subtitle') }}</p>
        </div>

        <div v-if="status" class="mb-4 rounded-lg bg-green-50 p-3 text-sm font-medium text-green-700 ring-1 ring-green-200">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">{{ t('auth.login.email') }}</label>
                <input
                    id="email"
                    type="email"
                    class="input-field mt-1.5"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    :placeholder="t('auth.login.email')"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">{{ t('auth.login.password') }}</label>
                <input
                    id="password"
                    type="password"
                    class="input-field mt-1.5"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    :placeholder="t('auth.login.password')"
                />
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="text-sm text-gray-600">{{ t('auth.login.remember') }}</span>
                </label>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm font-medium text-primary-600 transition hover:text-primary-700"
                >
                    {{ t('auth.login.forgot') }}
                </Link>
            </div>

            <button
                type="submit"
                class="btn-primary w-full !py-3"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                {{ t('auth.login.submit') }}
            </button>

            <p class="text-center text-sm text-gray-500">
                {{ t('auth.login.noAccount') }}
                <Link :href="route('register')" class="font-medium text-primary-600 transition hover:text-primary-700">
                    {{ t('auth.login.register') }}
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
