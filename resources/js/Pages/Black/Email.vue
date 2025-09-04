<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonDrawer from '@/Components/Common/CommonDrawer.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonDataTable from '@/Components/Common/CommonDataTable.vue';
import Column from 'primevue/column';
import CommonConfirmation from '@/Components/Common/CommonConfirmation.vue';

const props = defineProps(['emails']);

const showDrawer = ref(false);
const searchQuery = ref(null);
const openConfirmation = ref(false);
const openMultipleConfirmation = ref(false);
const deleteId = ref(null);

const form = useForm({
    email: '',
});

const selectForm = useForm({
    ids: null,
});

const handleOpen = () => {
    showDrawer.value = true;
};

const handleClose = () => {
    showDrawer.value = false;
    form.reset();
};

const handleDestroy = id => {
    deleteId.value = id;
    openConfirmation.value = true;
};

const handleCancel = () => {
    openConfirmation.value = false;
    deleteId.value = null;
};

const handleDelete = () => {
    form.delete(
        route('employee.black.email.destroy', { blackEmail: deleteId.value }),
        {
            onSuccess: () => {
                openConfirmation.value = false;
                deleteId.value = null;
            },
        }
    );
};

const handleSubmit = () => {
    form.post(route('employee.black.email.save'), {
        onSuccess: () => {
            handleClose();
        },
    });
};

const selectRows = e => {
    selectForm.ids = e?.map(item => item?.id);
};

const handleMultipleDelete = () => {
    selectForm.post(route('employee.black.email.destroys'), {
        onSuccess: () => {
            handleClose();
        },
    });
};

const handleMultipleCancel = () => {
    openMultipleConfirmation.value = false;
};

const handleMultipleConfirm = () => {
    openMultipleConfirmation.value = true;
};
</script>
<template>
    <PanelLayout title="Black List Email">
        <div class="rounded-md bg-white p-5">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-end justify-center gap-2">
                    <div>
                        <CommonInput
                            labelClass="font-semibold"
                            label="Search"
                            placeholder="Email"
                            :icon="'heroicons-outline:magnifying-glass'"
                            v-model="searchQuery"
                        />
                    </div>
                    <div>
                        <CommonButton
                            @click="handleMultipleConfirm"
                            variant="gray"
                            iconClass="!w-6 !h-6 text-red-500"
                            icon="heroicons-outline:trash"
                            class="p-2"
                            size="xs"
                        >
                        </CommonButton>
                    </div>
                </div>
                <div>
                    <CommonButton @click="handleOpen">Create</CommonButton>
                </div>
            </div>

            <CommonDataTable
                :showSerialNumber="true"
                :data="emails"
                checkbox
                @update:modelSelection="selectRows"
            >
                <Column field="email" header="Email" :sortable="true" />
                <Column
                    field="created_at"
                    header="Created At"
                    :sortable="true"
                />
                <Column header="Action" :sortable="false">
                    <template #body="slotProps">
                        <div class="flex gap-2">
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
        </div>

        <CommonDrawer
            v-model:visible="showDrawer"
            header="Create Black List Email"
        >
            <div class="mt-12 grid grid-cols-12 gap-3">
                <div class="col-span-12">
                    <CommonInput
                        v-model="form.email"
                        :error="form.errors.email"
                        label="Email"
                        type="email"
                        required
                    />
                </div>
            </div>

            <template #footer>
                <div class="flex justify-between gap-2">
                    <CommonButton variant="danger" @click="handleClose">
                        Close
                    </CommonButton>

                    <CommonButton @click="handleSubmit"> Save </CommonButton>
                </div>
            </template>
        </CommonDrawer>
        <!-- Confirmation modal for deletion -->
        <CommonConfirmation
            v-model="openConfirmation"
            title="Delete Confirmation"
            message="Are you sure you want to delete this item? This action cannot be undone."
            confirmText="Delete"
            cancelText="Cancel"
            @confirm="handleDelete"
            @cancel="handleCancel"
        />
        <CommonConfirmation
            v-model="openMultipleConfirmation"
            title="Delete Confirmation"
            message="Are you sure you want to delete this emails? This action cannot be undone."
            confirmText="Delete"
            cancelText="Cancel"
            @confirm="handleMultipleDelete"
            @cancel="handleMultipleCancel"
        />
    </PanelLayout>
</template>
