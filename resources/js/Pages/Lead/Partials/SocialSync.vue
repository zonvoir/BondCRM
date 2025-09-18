<script setup>
import CommonDatePicker from '@/Components/Common/CommonDatePicker.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import { ref } from 'vue';

const props = defineProps({
    handleSyncClose: { type: Function },
    scanAlgorithm: {
        type: Array,
        default: () => [],
    },
    status: {
        type: Array,
        default: () => [],
    },
});

const processing = ref(false);
const errors = ref({});
const isDateDisabled = ref();

const socialSyncForm = ref({
    provider: null,
    algorithm: null,
    startDate: '',
    endDate: '',
});

const selectedProvider = e => {
    isDateDisabled.value = !['Gmail', 'Outlook'].includes(e?.name);
    socialSyncForm.value.provider = e;
};
const handleSocialSyncSubmit = async () => {
    try {
        processing.value = true;
        const { data } = await axios.post(route('employee.lead.social'), {
            provider: socialSyncForm.value.provider,
            algorithm: socialSyncForm.value.algorithm,
            startDate: socialSyncForm.value.startDate,
            endDate: socialSyncForm.value.endDate,
        });

        console.log(data);
    } catch (e) {
        if (e.response?.status === 422 && e.response.data?.errors) {
            errors.value = e.response.data.errors;
        }
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <div
        class="mt-2 mb-3 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-900/40 dark:text-blue-300"
    >
        💡 <span class="font-medium">Note:</span> Deep Scan option is available
        only for <span class="font-semibold">Gmail</span> and
        <span class="font-semibold">Outlook</span>.
    </div>

    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-6">
            <CommonDatePicker
                v-model="socialSyncForm.startDate"
                label="Start Date"
                :disabled="isDateDisabled"
            />
        </div>
        <div class="col-span-6">
            <CommonDatePicker
                v-model="socialSyncForm.endDate"
                label="End Date"
                :disabled="isDateDisabled"
            />
        </div>

        <div class="col-span-12">
            <CommonSelect
                @update:modelValue="selectedProvider"
                label="Lead Source"
                :options="status"
                optionLabel="name"
                required
                :error="
                    errors['provider']?.[0] ||
                    errors['provider.name']?.[0] ||
                    errors['provider.code']?.[0]
                "
            />
        </div>

        <div class="col-span-12">
            <CommonSelect
                v-model="socialSyncForm.algorithm"
                label="Scan Algorithm"
                :options="scanAlgorithm"
                optionLabel="name"
                required
                :error="
                    errors['algorithm']?.[0] ||
                    errors['algorithm.name']?.[0] ||
                    errors['algorithm.code']?.[0]
                "
            />
        </div>
    </div>
    <div class="my-6 flex justify-between">
        <CommonButton variant="danger" @click="handleSyncClose"
            >Close</CommonButton
        >
        <CommonButton :processing="processing" @click="handleSocialSyncSubmit"
            >Save</CommonButton
        >
    </div>
</template>
