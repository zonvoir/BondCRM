<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonDrawer from '@/Components/Common/CommonDrawer.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonDataTable from '@/Components/Common/CommonDataTable.vue';
import Column from 'primevue/column';
import CommonModal from '@/Components/Common/CommonModal.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    user: Object,
});

const showDrawer = ref(false);

const form = useForm({
    file: '',
});

const handleClose = () => {
    showDrawer.value = !showDrawer.value;
};

const handleSubmit = () => {
    form.post(route('email.store'), {
        onSuccess: () => {
            handleClose();
            form.reset();
        },
    });
};
const isVisible = ref(false);
</script>
<template>
    <PanelLayout title="Tenants List">
        <div class="flex items-center justify-between py-4">
            <div></div>

            <div>
                <CommonButton @click="showDrawer = true"
                    >Upload <CommonIcon icon="solar:document-text-broken"
                /></CommonButton>
            </div>
        </div>
        <div
            class="border border-gray-200 p-4 shadow-sm sm:rounded-lg sm:p-2 dark:border-gray-700"
        >
            <CommonDataTable :data="user">
                <Column
                    field="name"
                    header="Name"
                    :sortable="true"
                    style="width: 20%"
                />
                <Column
                    field="email"
                    header="Email"
                    :sortable="true"
                    style="width: 20%"
                />
                <Column
                    field="created_at"
                    header="date"
                    :sortable="true"
                    style="width: 20%"
                />
            </CommonDataTable>

            <CommonDrawer v-model:visible="showDrawer" header="Tenant Regster">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-12">
                        <div class="py-3">
                            <input
                                type="file"
                                @input="form.file = $event.target.files[0]"
                            />
                        </div>
                    </div>
                </div>

                <template #footer>
                    <div class="flex justify-between gap-2">
                        <CommonButton variant="danger" @click="handleClose">
                            Close
                        </CommonButton>

                        <CommonButton @click="handleSubmit">
                            Save
                        </CommonButton>
                    </div>
                </template>
            </CommonDrawer>
        </div>
    </PanelLayout>
</template>
