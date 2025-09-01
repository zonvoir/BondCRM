<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonDrawer from '@/Components/Common/CommonDrawer.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonDatePicker from '@/Components/Common/CommonDatePicker.vue';

const props = defineProps({
    mailProviders: {
        type: Array,
    },
    scanAlgorithm: {
        type: Array,
    },
});

const searchQuery = ref(null);
const showDrawer = ref(false);
const isDateDisabled = ref();

const form = useForm({
    provider: '',
    algorithm: '',
    startDate: '',
    endDate: '',
});

const handleDrawerOpen = () => {
    showDrawer.value = true;
};

const handleDrawerClose = () => {
    showDrawer.value = false;
};

const selectedProvider = e => {
    isDateDisabled.value = !['gmail', 'outlook'].includes(e?.code);
    form.provider = e;
};
</script>

<template>
    <AppLayout title="Lead">
        <PanelLayout>
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center justify-center gap-2">
                    <div></div>
                </div>
                <div>
                    <CommonButton @click="handleDrawerOpen"
                        >Create</CommonButton
                    >
                </div>
            </div>

            <CommonDrawer v-model:visible="showDrawer" header="Create Lead">
                <div
                    class="mt-2 mb-3 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-900/40 dark:text-blue-300"
                >
                    💡 <span class="font-medium">Note:</span> Deep Scan option
                    is available only for
                    <span class="font-semibold">Gmail</span> and
                    <span class="font-semibold">Outlook</span>.
                </div>

                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-6">
                        <CommonDatePicker
                            v-model="form.startDate"
                            label="Start Date"
                            :disabled="isDateDisabled"
                        />
                    </div>
                    <div class="col-span-6">
                        <CommonDatePicker
                            v-model="form.endDate"
                            label="End Date"
                            :disabled="isDateDisabled"
                        />
                    </div>

                    <div class="col-span-12">
                        <CommonSelect
                            @update:modelValue="selectedProvider"
                            label="Lead Source"
                            :options="mailProviders"
                            optionLabel="name"
                            :error="form.errors.provider"
                            required
                        />
                    </div>

                    <div class="col-span-12">
                        <CommonSelect
                            v-model="form.algorithm"
                            label="Scan Algorithm"
                            :options="scanAlgorithm"
                            optionLabel="name"
                            :error="form.errors.algorithm"
                            required
                        />
                    </div>
                </div>
                <template #footer>
                    <div class="flex justify-between gap-2">
                        <CommonButton
                            variant="danger"
                            @click="handleDrawerClose"
                            >Close</CommonButton
                        >
                        <CommonButton>Save</CommonButton>
                    </div>
                </template>
            </CommonDrawer>
        </PanelLayout>
    </AppLayout>
</template>
