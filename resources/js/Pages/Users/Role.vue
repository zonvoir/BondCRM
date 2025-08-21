<script setup>
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonModal from '@/Components/Common/CommonModal.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import { useDebounce } from '@vueuse/core';
import Column from 'primevue/column';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonDataTable from '@/Components/Common/CommonDataTable.vue';
import CommonConfirmation from '@/Components/Common/CommonConfirmation.vue';
import CommonCheckbox from '@/Components/Common/CommonCheckbox.vue';

const props = defineProps({
    roles: {
        type: Object,
        required: true,
    },
    permissions: Object,
});

// UI State
const isVisible = ref(false);
const isVisiblePermissions = ref(false);
const openConfirmation = ref(false);
const deleteId = ref(null);
const searchQuery = ref(null);
const debouncedSearchQuery = useDebounce(searchQuery, 300);

// Forms
const form = useForm({
    id: null,
    name: '',
});

const formPermissions = useForm({
    role_id: null,
    name: [],
});

// Permissions State Management
const checkedStates = ref({});

const initializeCheckedStates = () => {
    if (!props.permissions) return;

    checkedStates.value = Object.entries(props.permissions).reduce(
        (modules, [moduleName, moduleData]) => {
            modules[moduleName] = {
                isChecked: false,
                actions: Object.entries(moduleData).reduce(
                    (actions, [actionName, permissions]) => {
                        actions[actionName] = {
                            isChecked: false,
                            permissions: permissions.reduce((perms, perm) => {
                                perms[perm] = false;
                                return perms;
                            }, {}),
                        };
                        return actions;
                    },
                    {}
                ),
            };
            return modules;
        },
        {}
    );
};

// Improved checkbox handling functions
const handleModuleCheck = (moduleName, isChecked) => {
    const module = checkedStates.value[moduleName];
    if (!module) return;

    module.isChecked = isChecked;
    Object.keys(module.actions).forEach(actionName => {
        handleActionCheck(moduleName, actionName, isChecked, false);
    });

    updateFormPermissions();
};

const handleActionCheck = (
    moduleName,
    actionName,
    isChecked,
    updateModule = true
) => {
    const action = checkedStates.value[moduleName]?.actions[actionName];
    if (!action) return;

    action.isChecked = isChecked;
    Object.keys(action.permissions).forEach(perm => {
        action.permissions[perm] = isChecked;
    });

    if (updateModule) {
        updateModuleState(moduleName);
    }
    updateFormPermissions();
};

const handlePermissionCheck = (
    moduleName,
    actionName,
    permission,
    isChecked
) => {
    const action = checkedStates.value[moduleName]?.actions[actionName];
    if (!action) return;

    action.permissions[permission] = isChecked;

    // Update action checked state
    action.isChecked = Object.values(action.permissions).every(
        checked => checked
    );

    // If any permission is checked, parent action should reflect partial selection
    if (
        !action.isChecked &&
        Object.values(action.permissions).some(checked => checked)
    ) {
        action.isChecked = null; // Partial selection state
    }

    updateModuleState(moduleName);
    updateFormPermissions();
};

const updateModuleState = moduleName => {
    const module = checkedStates.value[moduleName];
    if (!module) return;

    const actions = Object.values(module.actions);
    const allChecked = actions.every(action => action.isChecked === true);
    const someChecked = actions.some(
        action => action.isChecked === true || action.isChecked === null
    );

    module.isChecked = allChecked ? true : someChecked ? null : false;
};

const updateFormPermissions = () => {
    const checkedPermissions = [];

    Object.values(checkedStates.value).forEach(moduleData => {
        Object.values(moduleData.actions).forEach(actionData => {
            Object.entries(actionData.permissions).forEach(
                ([permission, isChecked]) => {
                    if (isChecked) {
                        checkedPermissions.push(permission);
                    }
                }
            );
        });
    });

    formPermissions.name = checkedPermissions;
};

// Role Management Functions
const handleOpen = () => {
    form.reset();
    isVisible.value = true;
};

const handleClose = () => {
    isVisible.value = false;
};

const handleEdit = data => {
    if (!data) return;

    form.id = data.id;
    form.name = data.name;
    isVisible.value = true;
};

const handleSubmit = () => {
    form.post(route('user.role.store'), {
        onSuccess: () => {
            handleClose();
            form.reset();
        },
    });
};

// Permission Management Functions
const handlePermissionsEdit = data => {
    if (!data?.permissions) return;

    formPermissions.role_id = data.id;
    isVisiblePermissions.value = true;
    initializeCheckedStates();

    const selectedPermissionNames = data.permissions.map(item => item.name);

    Object.entries(checkedStates.value).forEach(([moduleName, moduleData]) => {
        Object.entries(moduleData.actions).forEach(
            ([actionName, actionData]) => {
                let actionPermissionsChecked = 0;
                const totalPermissions = Object.keys(
                    actionData.permissions
                ).length;

                Object.keys(actionData.permissions).forEach(permission => {
                    const isChecked =
                        selectedPermissionNames.includes(permission);
                    actionData.permissions[permission] = isChecked;
                    if (isChecked) actionPermissionsChecked++;
                });

                // Set action check state (true, false, or null for partial)
                actionData.isChecked =
                    actionPermissionsChecked === totalPermissions
                        ? true
                        : actionPermissionsChecked > 0
                          ? null
                          : false;
            }
        );
        updateModuleState(moduleName);
    });

    updateFormPermissions();
};

const handlePermissionsSubmit = () => {
    formPermissions.post(route('user.permissions.assign.role'), {
        onSuccess: () => {
            isVisiblePermissions.value = false;
            formPermissions.reset();
        },
    });
};

// Delete Functions
const handleDestroy = id => {
    deleteId.value = id;
    openConfirmation.value = true;
};

const handleDelete = () => {
    form.delete(route('user.role.destroy', { role: deleteId.value }), {
        onSuccess: () => {
            openConfirmation.value = false;
            deleteId.value = null;
        },
    });
};

const handleCancel = () => {
    openConfirmation.value = false;
    deleteId.value = null;
};

// Watchers
watch(debouncedSearchQuery, newVal => {
    const queryParams = route()?.queryParams;
    router.get(
        route('user.role.index'),
        { ...queryParams, search: newVal },
        { preserveState: true, replace: true }
    );
});

watch(
    () => props.permissions,
    () => {
        initializeCheckedStates();
    },
    { deep: true }
);

// Initialize
initializeCheckedStates();
</script>

<template>
    <AppLayout title="Project">
        <PanelLayout>
            <!-- Header Section -->
            <div class="flex items-center justify-between py-4">
                <div>
                    <CommonInput label="Search" v-model="searchQuery" />
                </div>
                <div>
                    <CommonButton @click="handleOpen">Create</CommonButton>
                </div>
            </div>

            <!-- Role Modal -->
            <CommonModal
                v-model:visible="isVisible"
                :title="(form?.id ? 'Update' : 'Create') + ' Role'"
            >
                <div class="flex flex-col gap-10">
                    <div class="col-span-12 sm:col-span-4">
                        <CommonInput
                            label="Role Name"
                            required
                            v-model="form.name"
                            :error="form?.errors?.name"
                        />
                    </div>
                    <div class="flex justify-between gap-2">
                        <CommonButton @click="handleSubmit">Save</CommonButton>
                    </div>
                </div>
            </CommonModal>

            <!-- Permissions Modal -->
            <CommonModal
                v-model:visible="isVisiblePermissions"
                title="Manage Permissions"
            >
                <div class="flex flex-col gap-6">
                    <div class="permissions-container">
                        <div
                            v-for="(module, moduleName) in permissions"
                            :key="moduleName"
                            class="module-container mb-8 rounded-lg bg-white p-4 shadow-xs dark:bg-gray-900/80"
                        >
                            <!-- Module Header -->
                            <div
                                class="module-header mb-4 flex items-center gap-2 border-b border-gray-200 pb-2 dark:border-gray-700"
                            >
                                <CommonCheckbox
                                    :id="`module-${moduleName}`"
                                    v-model="
                                        checkedStates[moduleName].isChecked
                                    "
                                    @update:model-value="
                                        val =>
                                            handleModuleCheck(moduleName, val)
                                    "
                                    :indeterminate="
                                        checkedStates[moduleName].isChecked ===
                                        null
                                    "
                                    class="h-4 w-4 text-blue-600"
                                />
                                <label
                                    :for="`module-${moduleName}`"
                                    class="text-xl font-bold text-gray-800 capitalize dark:text-gray-200"
                                >
                                    {{ moduleName }}
                                </label>
                            </div>

                            <!-- Actions Grid -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                <template
                                    v-for="(permissions, actionName) in module"
                                    :key="actionName"
                                >
                                    <div
                                        v-if="permissions.length"
                                        class="action-container"
                                    >
                                        <div
                                            class="action-header mb-2 flex items-center gap-2"
                                        >
                                            <CommonCheckbox
                                                :id="`action-${moduleName}-${actionName}`"
                                                v-model="
                                                    checkedStates[moduleName]
                                                        .actions[actionName]
                                                        .isChecked
                                                "
                                                @update:model-value="
                                                    val =>
                                                        handleActionCheck(
                                                            moduleName,
                                                            actionName,
                                                            val
                                                        )
                                                "
                                                :indeterminate="
                                                    checkedStates[moduleName]
                                                        .actions[actionName]
                                                        .isChecked === null
                                                "
                                                class="h-4 w-4 text-blue-600"
                                            />
                                            <label
                                                :for="`action-${moduleName}-${actionName}`"
                                                class="font-semibold text-gray-700 capitalize dark:text-gray-300"
                                            >
                                                {{ actionName }}
                                            </label>
                                        </div>
                                        <div
                                            class="permissions-list ml-6 flex flex-col gap-2"
                                        >
                                            <div
                                                v-for="permission in permissions"
                                                :key="permission"
                                                class="permission-item flex items-center gap-2"
                                            >
                                                <CommonCheckbox
                                                    :id="`perm-${permission}`"
                                                    v-model="
                                                        checkedStates[
                                                            moduleName
                                                        ].actions[actionName]
                                                            .permissions[
                                                            permission
                                                        ]
                                                    "
                                                    @update:model-value="
                                                        val =>
                                                            handlePermissionCheck(
                                                                moduleName,
                                                                actionName,
                                                                permission,
                                                                val
                                                            )
                                                    "
                                                    class="h-4 w-4 text-blue-600"
                                                />
                                                <label
                                                    :for="`perm-${permission}`"
                                                    class="text-sm text-gray-600 dark:text-gray-400"
                                                >
                                                    {{ permission }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div
                        class="flex justify-end gap-2 border-t border-gray-200 pt-4 dark:border-gray-700"
                    >
                        <CommonButton @click="handlePermissionsSubmit">
                            Save Permissions
                        </CommonButton>
                    </div>
                </div>
            </CommonModal>

            <!-- Roles Table -->
            <CommonDataTable :data="roles">
                <Column field="name" header="Name" :sortable="true" />
                <Column header="Permissions" :sortable="false">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <CommonButton
                                type="button"
                                @click="handlePermissionsEdit(slotProps?.data)"
                                variant="secondary"
                            >
                                <CommonIcon icon="ion:finger-print-outline" />
                            </CommonButton>
                        </div>
                    </template>
                </Column>
                <Column header="Action" :sortable="false">
                    <template #body="slotProps">
                        <div class="flex gap-2">
                            <CommonButton
                                type="button"
                                @click="handleEdit(slotProps?.data)"
                                variant="secondary"
                            >
                                <CommonIcon icon="bi:pencil-square" />
                            </CommonButton>
                            <CommonButton
                                type="button"
                                @click="handleDestroy(slotProps?.data?.id)"
                                variant="secondary"
                            >
                                <CommonIcon icon="bi:trash" />
                            </CommonButton>
                        </div>
                    </template>
                </Column>
            </CommonDataTable>

            <!-- Delete Confirmation -->
            <CommonConfirmation
                v-model="openConfirmation"
                title="Delete Confirmation"
                message="Are you sure you want to delete this item? This action cannot be undone."
                confirmText="Delete"
                cancelText="Cancel"
                @confirm="handleDelete"
                @cancel="handleCancel"
            />
        </PanelLayout>
    </AppLayout>
</template>
