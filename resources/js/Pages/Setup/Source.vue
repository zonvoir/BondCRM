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
import { useSelectMapper } from '@/Composables/useSelectMapper.js';

const props = defineProps(['sources']);

const showDrawer = ref(false);
const searchQuery = ref(null);
const openConfirmation = ref(false);
const deleteId = ref(null);

const form = useForm({
    id: null,
    source: '',
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
        route('setup.source.destroy', {
            source: deleteId.value,
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
    form.post(route('setup.source.save'), {
        onSuccess: () => {
            handleClose();
        },
    });
};

const handleEdit = data => {
    showDrawer.value = true;
    form.source = data?.source;
    form.id = data?.id;
};
</script>
<template>
    <PanelLayout title="Sources List">
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center justify-center gap-2">
                <div>
                    <CommonInput label="Search" v-model="searchQuery" />
                </div>
            </div>
            <div>
                <CommonButton @click="handleOpen">Create</CommonButton>
            </div>
        </div>

        <CommonDataTable
            routeName="setup.source"
            :showSerialNumber="true"
            :data="sources"
        >
            <Column field="source" header="Name" :sortable="true" />
            <Column field="created_at" header="Created At" :sortable="true" />
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

        <CommonDrawer v-model:visible="showDrawer" header="Create status">
            <div class="mt-12 grid grid-cols-12 gap-3">
                <div class="col-span-12">
                    <CommonInput
                        v-model="form.source"
                        :error="form.errors.source"
                        label="Name"
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
    </PanelLayout>
</template>
