<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref, computed } from 'vue';
import PageHeader from '@/Components/PageHeader.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

interface Member {
    id: string;
    user_id: string;
    role: string;
    joined_at: string;
    user: {
        id: string;
        name: string;
        email: string;
    };
}

interface Invitation {
    id: string;
    email: string;
    role: string;
    status: string;
    expires_at: string;
    created_at: string;
    inviter: {
        name: string;
    };
}

interface RoleOption {
    value: string;
    label: string;
}

const props = defineProps<{
    members: Member[];
    pendingInvitations: Invitation[];
    currentUserRole: string | null;
    availableRoles: RoleOption[];
}>();

const { t } = useI18n();
const page = usePage();

const showInviteModal = ref(false);
const showRoleModal = ref(false);
const selectedMember = ref<Member | null>(null);

const canManage = computed(() => {
    return props.currentUserRole === 'owner' || props.currentUserRole === 'admin';
});

const inviteForm = useForm({
    email: '',
    role: 'member',
    personal_message: '',
});

const roleForm = useForm({
    role: '',
});

const assignableRoles = computed(() => {
    const hierarchy: Record<string, number> = {
        owner: 100, admin: 80, leader: 60, member: 40, viewer: 20,
    };
    const currentLevel = hierarchy[props.currentUserRole ?? ''] ?? 0;
    return props.availableRoles.filter(r => hierarchy[r.value] < currentLevel);
});

const openInviteModal = () => {
    inviteForm.reset();
    showInviteModal.value = true;
};

const submitInvite = () => {
    inviteForm.post(route('team.invitations.store'), {
        onSuccess: () => {
            showInviteModal.value = false;
            inviteForm.reset();
        },
    });
};

const openRoleModal = (member: Member) => {
    selectedMember.value = member;
    roleForm.role = member.role;
    showRoleModal.value = true;
};

const submitRoleChange = () => {
    if (!selectedMember.value) return;
    roleForm.patch(route('team.members.update-role', selectedMember.value.id), {
        onSuccess: () => {
            showRoleModal.value = false;
            selectedMember.value = null;
        },
    });
};

const removeMember = (member: Member) => {
    if (!confirm(t('team.confirmRemove'))) return;
    router.delete(route('team.members.destroy', member.id));
};

const revokeInvitation = (invitation: Invitation) => {
    if (!confirm(t('team.confirmRevoke'))) return;
    router.delete(route('team.invitations.revoke', invitation.id));
};

const roleColor = (role: string): string => {
    const colors: Record<string, string> = {
        owner: 'bg-amber-100 text-amber-800',
        admin: 'bg-purple-100 text-purple-800',
        leader: 'bg-blue-100 text-blue-800',
        member: 'bg-green-100 text-green-800',
        viewer: 'bg-gray-100 text-gray-600',
    };
    return colors[role] ?? 'bg-gray-100 text-gray-600';
};
</script>

<template>
    <Head :title="t('team.title')" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <div class="flex items-center justify-between">
                <PageHeader :title="t('team.title')" :subtitle="t('team.subtitle')" />
                <PrimaryButton v-if="canManage" @click="openInviteModal">
                    {{ t('team.invite') }}
                </PrimaryButton>
            </div>

            <!-- Members List -->
            <div class="card">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">{{ t('team.members') }} ({{ members.length }})</h3>
                <div class="divide-y divide-gray-100">
                    <div v-for="member in members" :key="member.id" class="flex items-center justify-between py-4">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700">
                                {{ member.user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ member.user.name }}</p>
                                <p class="text-sm text-gray-500">{{ member.user.email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', roleColor(member.role)]">
                                {{ t('team.roles.' + member.role) }}
                            </span>
                            <div v-if="canManage && member.role !== 'owner' && member.user_id !== String($page.props.auth.user.id)" class="flex gap-2">
                                <button @click="openRoleModal(member)" class="text-sm text-primary-600 hover:text-primary-800">
                                    {{ t('team.changeRole') }}
                                </button>
                                <button @click="removeMember(member)" class="text-sm text-red-600 hover:text-red-800">
                                    {{ t('common.delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="members.length === 0" class="py-8 text-center text-gray-500">
                        {{ t('team.noMembers') }}
                    </div>
                </div>
            </div>

            <!-- Pending Invitations -->
            <div v-if="canManage && pendingInvitations.length > 0" class="card">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">{{ t('team.pendingInvitations') }} ({{ pendingInvitations.length }})</h3>
                <div class="divide-y divide-gray-100">
                    <div v-for="invitation in pendingInvitations" :key="invitation.id" class="flex items-center justify-between py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ invitation.email }}</p>
                            <p class="text-sm text-gray-500">
                                {{ t('team.invitedBy') }} {{ invitation.inviter.name }} &middot;
                                {{ t('team.expiresAt') }} {{ new Date(invitation.expires_at).toLocaleDateString() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', roleColor(invitation.role)]">
                                {{ t('team.roles.' + invitation.role) }}
                            </span>
                            <button @click="revokeInvitation(invitation)" class="text-sm text-red-600 hover:text-red-800">
                                {{ t('team.revoke') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invite Modal -->
            <Modal :show="showInviteModal" @close="showInviteModal = false">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900">{{ t('team.inviteMember') }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ t('team.inviteSubtitle') }}</p>

                    <form @submit.prevent="submitInvite" class="mt-6 space-y-4">
                        <div>
                            <InputLabel for="email" :value="t('team.email')" />
                            <TextInput
                                id="email"
                                v-model="inviteForm.email"
                                type="email"
                                class="mt-1 block w-full"
                                :placeholder="t('team.emailPlaceholder')"
                                required
                            />
                            <InputError :message="inviteForm.errors.email" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="role" :value="t('team.role')" />
                            <select
                                id="role"
                                v-model="inviteForm.role"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                                <option v-for="role in assignableRoles" :key="role.value" :value="role.value">
                                    {{ t('team.roles.' + role.value) }}
                                </option>
                            </select>
                            <InputError :message="inviteForm.errors.role" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="personal_message" :value="t('team.personalMessage')" />
                            <textarea
                                id="personal_message"
                                v-model="inviteForm.personal_message"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                rows="3"
                                :placeholder="t('team.personalMessagePlaceholder')"
                            />
                            <InputError :message="inviteForm.errors.personal_message" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <SecondaryButton @click="showInviteModal = false">{{ t('common.cancel') }}</SecondaryButton>
                            <PrimaryButton :disabled="inviteForm.processing">{{ t('team.sendInvite') }}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>

            <!-- Role Change Modal -->
            <Modal :show="showRoleModal" @close="showRoleModal = false">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900">{{ t('team.changeRoleTitle') }}</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ t('team.changeRoleFor') }} <strong>{{ selectedMember?.user.name }}</strong>
                    </p>

                    <form @submit.prevent="submitRoleChange" class="mt-6 space-y-4">
                        <div>
                            <InputLabel for="new_role" :value="t('team.newRole')" />
                            <select
                                id="new_role"
                                v-model="roleForm.role"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                                <option v-for="role in assignableRoles" :key="role.value" :value="role.value">
                                    {{ t('team.roles.' + role.value) }}
                                </option>
                            </select>
                            <InputError :message="roleForm.errors.role" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3">
                            <SecondaryButton @click="showRoleModal = false">{{ t('common.cancel') }}</SecondaryButton>
                            <PrimaryButton :disabled="roleForm.processing">{{ t('common.save') }}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
