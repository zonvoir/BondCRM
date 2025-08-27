<script setup>
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import { useDebounce } from '@vueuse/core';
import CommonDrawer from '@/Components/Common/CommonDrawer.vue';
import CommonDataTable from '@/Components/Common/CommonDataTable.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import Column from 'primevue/column';
import CommonConfirmation from '@/Components/Common/CommonConfirmation.vue';
import { useSelectMapper } from '@/Composables/useSelectMapper.js';
import CommonBadge from '@/Components/Common/CommonBadge.vue';
import CommonCheckbox from '@/Components/Common/CommonCheckbox.vue';
import CommonModal from '@/Components/Common/CommonModal.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';

const props = defineProps({
    users: Object,
    roles: Array,
    permissions: Object,
});

const isVisible = ref(false);
const isVisiblePermissions = ref(false);
const openConfirmation = ref(false);
const deleteId = ref(null);
const searchQuery = ref(null);
const debouncedSearchQuery = useDebounce(searchQuery, 300);
const filteredPermissions = ref({});

const form = useForm({
    id: null,
    roles: '',
    name: '',
    email: '',
    password: '',
    passwordConfirmation: '',
});

const formPermissions = useForm({
    user_id: null,
    permissions: [],
});

const handleOpen = () => {
    form.reset();
    isVisible.value = true;
};

const handleClose = () => {
    isVisible.value = !isVisible.value;
};

const handleEdit = data => {
    const { mappedData: mappedRoles } = useSelectMapper(data.role, false);

    isVisible.value = true;
    form.id = data?.id;
    form.name = data?.name;
    form.email = data?.email;
    form.roles = mappedRoles?.value;
};

const handleSubmit = () => {
    form.post(route('user.store'), {
        onSuccess: () => {
            handleClose();
            form.reset();
        },
    });
};

const handleDestroy = id => {
    deleteId.value = id;
    openConfirmation.value = true;
};

const handleDelete = () => {
    form.delete(route('user.destroy', { user: deleteId.value }), {
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

watch(debouncedSearchQuery, newVal => {
    const queryParams = route()?.queryParams;
    const updatedQuery = { ...queryParams, search: newVal };
    router.get(route('user.list'), updatedQuery, {
        preserveState: true,
        replace: true,
    });
});

const handlePermissionsSubmit = () => {
    formPermissions.post(route('user.permissions.assign'), {
        onSuccess: () => {
            isVisiblePermissions.value = false;
        },
    });
};

const handlePermission = data => {
    isVisiblePermissions.value = true;

    const { role, permissions: userPermissions } = data;
    const rolePermissions = role.permissions.map(p => p.name);
    const allowed = new Set(rolePermissions);

    filteredPermissions.value = Object.fromEntries(
        Object.entries(props.permissions)
            .map(([module, actions]) => {
                const filteredActions = Object.fromEntries(
                    Object.entries(actions)
                        .map(([action, perms]) => [
                            action,
                            perms.filter(p => allowed.has(p)),
                        ])
                        .filter(([, perms]) => perms.length > 0)
                );
                return Object.keys(filteredActions).length > 0
                    ? [module, filteredActions]
                    : null;
            })
            .filter(Boolean)
    );

    formPermissions.user_id = data.id;
    formPermissions.permissions = userPermissions.map(p => p.name);
};

const togglePermission = permissionName => {
    const index = formPermissions.permissions.indexOf(permissionName);
    if (index === -1) {
        formPermissions.permissions.push(permissionName);
    } else {
        formPermissions.permissions.splice(index, 1);
    }
};

const toggleActionGroup = permissions => {
    const allChecked = permissions.every(p =>
        formPermissions.permissions.includes(p)
    );
    if (allChecked) {
        formPermissions.permissions = formPermissions.permissions.filter(
            p => !permissions.includes(p)
        );
    } else {
        formPermissions.permissions = [
            ...new Set([...formPermissions.permissions, ...permissions]),
        ];
    }
};

const toggleModuleGroup = module => {
    const allPermissions = Object.values(
        filteredPermissions.value[module]
    ).flat();
    const allChecked = allPermissions.every(p =>
        formPermissions.permissions.includes(p)
    );
    if (allChecked) {
        formPermissions.permissions = formPermissions.permissions.filter(
            p => !allPermissions.includes(p)
        );
    } else {
        formPermissions.permissions = [
            ...new Set([...formPermissions.permissions, ...allPermissions]),
        ];
    }
};

const isChecked = permissionName => {
    return formPermissions.permissions.includes(permissionName);
};

const isActionGroupChecked = permissions =>
    permissions.every(p => isChecked(p));
const isModuleGroupChecked = actions =>
    Object.values(actions)
        .flat()
        .every(p => isChecked(p));
</script>

<template>
    <AppLayout title="Users">
        <PanelLayout>
            <div class="flex items-center justify-between py-4">
                <div>
                    <CommonInput label="Search" v-model="searchQuery" />
                </div>
                <div>
                    <CommonButton @click="handleOpen">Create</CommonButton>
                </div>
            </div>

            <CommonDataTable :showSerialNumber="true" :data="users">
                <Column field="name" header="Name" :sortable="true" />
                <Column field="email" header="Email" :sortable="true" />
                <Column header="Roles / Permissions" :sortable="false">
                    <template #body="slotProps">
                        <CommonBadge
                            class="cursor-pointer"
                            v-if="slotProps.data?.role?.name"
                            @click="handlePermission(slotProps.data)"
                            :value="slotProps.data?.role?.name"
                        />
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

            <CommonConfirmation
                v-model="openConfirmation"
                title="Delete Confirmation"
                message="Are you sure you want to delete this item? This action cannot be undone."
                confirmText="Delete"
                cancelText="Cancel"
                @confirm="handleDelete"
                @cancel="handleCancel"
            />

            <CommonDrawer v-model:visible="isVisible" header="User">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12">
                        <CommonSelect
                            v-model="form.roles"
                            label="Role"
                            :options="roles"
                            optionLabel="name"
                            :error="form.errors.roles"
                            required
                        />
                    </div>
                    <div class="col-span-12">
                        <CommonInput
                            v-model="form.name"
                            :error="form.errors.name"
                            label="Name"
                            required
                        />
                    </div>
                    <div class="col-span-12">
                        <CommonInput
                            v-model="form.email"
                            :error="form.errors.email"
                            label="Email"
                            type="email"
                            required
                        />
                    </div>

                    <template v-if="!form.id">
                        <div class="col-span-12">
                            <CommonInput
                                v-model="form.password"
                                :error="form.errors.password"
                                label="Password"
                                type="password"
                                required
                            />
                        </div>
                        <div class="col-span-12">
                            <CommonInput
                                v-model="form.passwordConfirmation"
                                :error="form.errors.passwordConfirmation"
                                label="Confirm Password"
                                type="password"
                                required
                            />
                        </div>
                    </template>
                </div>
                <template #footer>
                    <div class="flex justify-between gap-2">
                        <CommonButton variant="danger" @click="handleClose"
                            >Close</CommonButton
                        >
                        <CommonButton @click="handleSubmit">Save</CommonButton>
                    </div>
                </template>
            </CommonDrawer>

            <CommonModal
                v-model:visible="isVisiblePermissions"
                title="Manage Permissions"
            >
                <div class="flex flex-col gap-6">
                    <div class="permissions-container">
                        <div
                            v-for="(actions, module) in filteredPermissions"
                            :key="module"
                            class="module-container mb-8 rounded-lg bg-white p-4 shadow-xs dark:bg-gray-900/80"
                        >
                            <div
                                class="module-header mb-4 flex items-center gap-2 border-b border-gray-200 pb-2 dark:border-gray-700"
                            >
                                <CommonCheckbox
                                    class="h-4 w-4 text-blue-600"
                                    :modelValue="isModuleGroupChecked(actions)"
                                    @update:modelValue="
                                        () => toggleModuleGroup(module)
                                    "
                                />
                                <label
                                    class="text-xl font-bold text-gray-800 capitalize dark:text-gray-200"
                                >
                                    {{ module }}
                                </label>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                <div
                                    v-for="(permissions, action) in actions"
                                    :key="action"
                                    class="action-container"
                                >
                                    <div
                                        class="action-header mb-2 flex items-center gap-2"
                                    >
                                        <CommonCheckbox
                                            class="h-4 w-4 text-blue-600"
                                            :modelValue="
                                                isActionGroupChecked(
                                                    permissions
                                                )
                                            "
                                            @update:modelValue="
                                                () =>
                                                    toggleActionGroup(
                                                        permissions
                                                    )
                                            "
                                        />
                                        <label
                                            class="font-semibold text-gray-700 capitalize dark:text-gray-300"
                                        >
                                            {{ action }}
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
                                                class="h-4 w-4 text-blue-600"
                                                :modelValue="
                                                    isChecked(permission)
                                                "
                                                @update:modelValue="
                                                    () =>
                                                        togglePermission(
                                                            permission
                                                        )
                                                "
                                            />
                                            <label
                                                class="text-sm text-gray-600 dark:text-gray-400"
                                            >
                                                {{ permission }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex justify-end gap-2 border-t border-gray-200 pt-4 dark:border-gray-700"
                    >
                        <CommonButton @click="handlePermissionsSubmit">
                            Save Permissions
                        </CommonButton>
                    </div>
                </div>
            </CommonModal>
        </PanelLayout>
    </AppLayout>
</template>
