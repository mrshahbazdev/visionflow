<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { ref, computed } from 'vue';
import PageHeader from '@/Components/PageHeader.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

interface Member {
    id: string;
    user_id: string;
    role: string;
    user: {
        id: string;
        name: string;
        email: string;
    };
}

interface Assignment {
    id: string;
    assignable_type: string;
    assignable_id: string;
    role_in_assignment: string;
    notes: string | null;
    status: string;
    due_at: string | null;
    completed_at: string | null;
    created_at: string;
    assigned_by: { name: string };
    assigned_to: { name: string };
    assignable: { title?: string; name?: string } | null;
}

interface IdTitle {
    id: string;
    title?: string;
    name?: string;
}

const props = defineProps<{
    assignments: Assignment[];
    members: Member[];
    missions: IdTitle[];
    projects: IdTitle[];
}>();

const { t } = useI18n();
const page = usePage();

const showAssignModal = ref(false);
const currentUserRole = computed(() => (page.props.auth as any).teamRole);
const canAssign = computed(() => ['owner', 'admin', 'leader'].includes(currentUserRole.value ?? ''));

const assignForm = useForm({
    assigned_to: '',
    assignable_type: 'mission',
    assignable_id: '',
    role_in_assignment: 'responsible',
    notes: '',
    due_at: '',
});

const assignableItems = computed(() => {
    return assignForm.assignable_type === 'mission' ? props.missions : props.projects;
});

const openAssignModal = () => {
    assignForm.reset();
    showAssignModal.value = true;
};

const submitAssignment = () => {
    assignForm.post(route('team.assignments.store'), {
        onSuccess: () => {
            showAssignModal.value = false;
            assignForm.reset();
        },
    });
};

const completeAssignment = (assignment: Assignment) => {
    router.patch(route('team.assignments.complete', assignment.id));
};

const deleteAssignment = (assignment: Assignment) => {
    if (!confirm(t('team.assignments.confirmDelete'))) return;
    router.delete(route('team.assignments.destroy', assignment.id));
};

const statusColor = (status: string): string => {
    const colors: Record<string, string> = {
        active: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
    };
    return colors[status] ?? 'bg-gray-100 text-gray-600';
};

const raciLabel = (role: string): string => {
    const labels: Record<string, string> = {
        responsible: 'R',
        accountable: 'A',
        consulted: 'C',
        informed: 'I',
    };
    return labels[role] ?? role;
};
</script>

<template>
    <Head :title="t('team.assignments.title')" />

    <AuthenticatedLayout>
        <div class="space-y-8">
            <div class="flex items-center justify-between">
                <PageHeader :title="t('team.assignments.title')" :subtitle="t('team.assignments.subtitle')" />
                <PrimaryButton v-if="canAssign" @click="openAssignModal">
                    {{ t('team.assignments.assign') }}
                </PrimaryButton>
            </div>

            <!-- Assignments List -->
            <div class="card">
                <div class="divide-y divide-gray-100">
                    <div v-for="assignment in assignments" :key="assignment.id" class="flex items-center justify-between py-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="font-medium text-gray-900">
                                    {{ assignment.assignable?.title ?? assignment.assignable?.name ?? t('team.assignments.unknownItem') }}
                                </p>
                                <span class="inline-flex h-5 w-5 items-center justify-center rounded bg-primary-100 text-xs font-bold text-primary-700">
                                    {{ raciLabel(assignment.role_in_assignment) }}
                                </span>
                                <span :class="['inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium', statusColor(assignment.status)]">
                                    {{ t('team.assignments.statuses.' + assignment.status) }}
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ t('team.assignments.assignedTo') }}: <strong>{{ assignment.assigned_to.name }}</strong>
                                &middot; {{ t('team.assignments.by') }} {{ assignment.assigned_by.name }}
                            </p>
                            <p v-if="assignment.notes" class="mt-1 text-sm italic text-gray-400">{{ assignment.notes }}</p>
                            <p v-if="assignment.due_at" class="mt-1 text-xs text-gray-400">
                                {{ t('team.assignments.dueAt') }}: {{ new Date(assignment.due_at).toLocaleDateString() }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <button
                                v-if="assignment.status === 'active'"
                                @click="completeAssignment(assignment)"
                                class="rounded-lg bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100"
                            >
                                {{ t('team.assignments.markComplete') }}
                            </button>
                            <button
                                v-if="canAssign"
                                @click="deleteAssignment(assignment)"
                                class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100"
                            >
                                {{ t('common.delete') }}
                            </button>
                        </div>
                    </div>
                    <div v-if="assignments.length === 0" class="py-12 text-center text-gray-500">
                        {{ t('team.assignments.empty') }}
                    </div>
                </div>
            </div>

            <!-- Assign Work Modal -->
            <Modal :show="showAssignModal" @close="showAssignModal = false">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900">{{ t('team.assignments.assign') }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ t('team.assignments.assignSubtitle') }}</p>

                    <form @submit.prevent="submitAssignment" class="mt-6 space-y-4">
                        <div>
                            <InputLabel for="assigned_to" :value="t('team.assignments.assignTo')" />
                            <select
                                id="assigned_to"
                                v-model="assignForm.assigned_to"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                required
                            >
                                <option value="">{{ t('team.assignments.selectMember') }}</option>
                                <option v-for="member in members" :key="member.user.id" :value="member.user.id">
                                    {{ member.user.name }} ({{ member.user.email }})
                                </option>
                            </select>
                            <InputError :message="assignForm.errors.assigned_to" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="assignable_type" :value="t('team.assignments.type')" />
                            <select
                                id="assignable_type"
                                v-model="assignForm.assignable_type"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                                <option value="mission">{{ t('team.assignments.mission') }}</option>
                                <option value="project">{{ t('team.assignments.project') }}</option>
                            </select>
                        </div>

                        <div>
                            <InputLabel for="assignable_id" :value="t('team.assignments.item')" />
                            <select
                                id="assignable_id"
                                v-model="assignForm.assignable_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                required
                            >
                                <option value="">{{ t('team.assignments.selectItem') }}</option>
                                <option v-for="item in assignableItems" :key="item.id" :value="item.id">
                                    {{ item.title ?? item.name }}
                                </option>
                            </select>
                            <InputError :message="assignForm.errors.assignable_id" class="mt-2" />
                        </div>

                        <div>
                            <InputLabel for="role_in_assignment" :value="t('team.assignments.raciRole')" />
                            <select
                                id="role_in_assignment"
                                v-model="assignForm.role_in_assignment"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            >
                                <option value="responsible">{{ t('team.assignments.raci.responsible') }}</option>
                                <option value="accountable">{{ t('team.assignments.raci.accountable') }}</option>
                                <option value="consulted">{{ t('team.assignments.raci.consulted') }}</option>
                                <option value="informed">{{ t('team.assignments.raci.informed') }}</option>
                            </select>
                        </div>

                        <div>
                            <InputLabel for="notes" :value="t('team.assignments.notes')" />
                            <textarea
                                id="notes"
                                v-model="assignForm.notes"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                rows="2"
                                :placeholder="t('team.assignments.notesPlaceholder')"
                            />
                        </div>

                        <div>
                            <InputLabel for="due_at" :value="t('team.assignments.dueAt')" />
                            <input
                                id="due_at"
                                v-model="assignForm.due_at"
                                type="date"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            />
                        </div>

                        <div class="flex justify-end gap-3">
                            <SecondaryButton @click="showAssignModal = false">{{ t('common.cancel') }}</SecondaryButton>
                            <PrimaryButton :disabled="assignForm.processing">{{ t('team.assignments.assign') }}</PrimaryButton>
                        </div>
                    </form>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
