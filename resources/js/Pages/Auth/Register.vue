<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="t('auth.register.title')" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ t('auth.register.title') }}</h1>
            <p class="mt-2 text-sm text-gray-500">{{ t('auth.register.subtitle') }}</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">{{ t('auth.register.name') }}</label>
                <input
                    id="name"
                    type="text"
                    class="input-field mt-1.5"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="t('auth.register.name')"
                />
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">{{ t('auth.register.email') }}</label>
                <input
                    id="email"
                    type="email"
                    class="input-field mt-1.5"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :placeholder="t('auth.register.email')"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">{{ t('auth.register.password') }}</label>
                <input
                    id="password"
                    type="password"
                    class="input-field mt-1.5"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    :placeholder="t('auth.register.password')"
                />
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ t('auth.register.confirmPassword') }}</label>
                <input
                    id="password_confirmation"
                    type="password"
                    class="input-field mt-1.5"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    :placeholder="t('auth.register.confirmPassword')"
                />
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <button
                type="submit"
                class="btn-primary w-full !py-3"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                {{ t('auth.register.submit') }}
            </button>

            <p class="text-center text-sm text-gray-500">
                {{ t('auth.register.hasAccount') }}
                <Link :href="route('login')" class="font-medium text-primary-600 transition hover:text-primary-700">
                    {{ t('auth.register.login') }}
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
