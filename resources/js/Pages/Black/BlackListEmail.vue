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
import CommonFile from '@/Components/Common/CommonFile.vue';

const props = defineProps(['blackListEmail']);

const showDrawer = ref(false);
const searchQuery = ref(null);
const isVisible = ref(false);
const openConfirmation = ref(false);
const deleteId = ref(null);

const form = useForm({
    file: '',
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
        route('email.blacklist.destroy', { blackList: deleteId.value }),
        {
            onSuccess: () => {
                openConfirmation.value = false;
                deleteId.value = null;
            },
        }
    );
};

const handleSubmit = () => {
    form.post(route('email.blacklist.save'), {
        onSuccess: () => {
            handleClose();
        },
    });
};
</script>
<template>
    <PanelLayout title="Black List Email">
        <div class="flex items-center justify-between py-4">
            <div>
                <CommonInput label="Search" v-model="searchQuery" />
            </div>
            <div>
                <CommonButton @click="handleOpen">Create</CommonButton>
            </div>
        </div>

        <CommonDataTable :data="blackListEmail">
            <Column field="file" header="File" :sortable="true" />

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
            header="Upload Black List Email "
        >
            <div class="flex flex-col space-y-2">
                <span class="flex items-center gap-2 font-semibold">
                    <CommonIcon class="text-red-600" icon="solar:pin-bold" />
                    Important Notes
                </span>
                <ul class="list-inside list-disc text-sm">
                    <li>
                        Each line must contain only the domain name – no extra
                        symbols, no comments.
                    </li>
                    <li>
                        The package will automatically detect and use the
                        domains from this file.
                    </li>
                    <li>
                        <strong
                            >Only <code>.txt</code> files are allowed for
                            upload.</strong
                        >
                    </li>
                </ul>
            </div>

            <div class="mt-12 grid grid-cols-12 gap-3">
                <div class="col-span-12">
                    <CommonFile
                        accepted-formats=".txt"
                        v-model="form.file"
                        :error="form.errors.file"
                        label="File"
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
