<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

interface Invitation {
    id: string;
    token: string;
    email: string;
    role: string;
    personal_message: string | null;
    expires_at: string;
    organization: {
        name: string;
        description: string | null;
    };
    inviter: {
        name: string;
    };
}

const props = defineProps<{
    invitation: Invitation;
}>();

const { t } = useI18n();
const page = usePage();
const isAuthenticated = !!page.props.auth?.user;

const form = useForm({});

const acceptInvitation = () => {
    form.post(route('team.invitations.accept', props.invitation.token));
};
</script>

<template>
    <Head :title="t('team.acceptInvitation.title')" />

    <GuestLayout>
        <div class="space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900">{{ t('team.acceptInvitation.title') }}</h2>
                <p class="mt-2 text-sm text-gray-500">{{ t('team.acceptInvitation.subtitle') }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-gray-50 p-6">
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ t('team.acceptInvitation.organization') }}</span>
                        <p class="text-lg font-semibold text-gray-900">{{ invitation.organization.name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ t('team.acceptInvitation.invitedBy') }}</span>
                        <p class="text-gray-900">{{ invitation.inviter.name }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ t('team.role') }}</span>
                        <p class="text-gray-900">{{ t('team.roles.' + invitation.role) }}</p>
                    </div>
                    <div v-if="invitation.personal_message">
                        <span class="text-sm font-medium text-gray-500">{{ t('team.personalMessage') }}</span>
                        <p class="italic text-gray-700">"{{ invitation.personal_message }}"</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">{{ t('team.expiresAt') }}</span>
                        <p class="text-gray-900">{{ new Date(invitation.expires_at).toLocaleDateString() }}</p>
                    </div>
                </div>
            </div>

            <div v-if="isAuthenticated" class="flex gap-3">
                <PrimaryButton @click="acceptInvitation" :disabled="form.processing" class="flex-1 justify-center">
                    {{ t('team.acceptInvitation.accept') }}
                </PrimaryButton>
            </div>

            <div v-else class="space-y-3">
                <p class="text-center text-sm text-gray-500">{{ t('team.acceptInvitation.loginRequired') }}</p>
                <div class="flex gap-3">
                    <Link :href="route('login')" class="btn-primary flex-1 text-center">
                        {{ t('nav.login') }}
                    </Link>
                    <Link :href="route('register')" class="btn-secondary flex-1 text-center">
                        {{ t('nav.register') }}
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
