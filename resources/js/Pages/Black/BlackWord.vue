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

const props = defineProps(['keywords']);

const showDrawer = ref(false);
const searchQuery = ref(null);
const openConfirmation = ref(false);
const openMultipleConfirmation = ref(false);
const deleteId = ref(null);

const form = useForm({
    keyword: '',
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
        route('employee.black.word.destroy', {
            blackListKeyword: deleteId.value,
        }),
        {
            onSuccess: () => {
                openConfirmation.value = false;
                deleteId.value = null;
            },
        }
    );
};

const handleSubmit = () => {
    form.post(route('employee.black.word.save'), {
        onSuccess: () => {
            handleClose();
        },
    });
};

const selectRows = e => {
    selectForm.ids = e?.map(item => item?.id);
};

const handleMultipleDelete = () => {
    selectForm.post(route('employee.black.work.destroys'), {
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
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center justify-center gap-2">
                <div>
                    <CommonInput label="Search" v-model="searchQuery" />
                </div>
                <div>
                    <CommonButton
                        @click="handleMultipleConfirm"
                        variant="secondary"
                        size="xs"
                    >
                        <CommonIcon
                            class="h-7 w-7 text-gray-500"
                            icon="material-symbols:delete"
                        />
                    </CommonButton>
                </div>
            </div>
            <div>
                <CommonButton @click="handleOpen">Create</CommonButton>
            </div>
        </div>

        <CommonDataTable
            :showSerialNumber="true"
            :data="keywords"
            checkbox
            @update:modelSelection="selectRows"
        >
            <Column field="keyword" header="Keyword" :sortable="true" />
            <Column field="created_at" header="Created At" :sortable="true" />
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

        <CommonDrawer
            v-model:visible="showDrawer"
            header="Create Black List Keyword"
        >
            <div class="mt-12 grid grid-cols-12 gap-3">
                <div class="col-span-12">
                    <CommonInput
                        v-model="form.keyword"
                        :error="form.errors.keyword"
                        label="keyword"
                        type="text"
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
