<template>
    <div :class="wrapperClass">
        <label
            v-if="label"
            class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
            <span class="flex items-center">
                {{ label }}
                <span v-if="required" class="mx-1 text-red-600">*</span>
            </span>
        </label>

        <DatePicker
            v-model="localValue"
            :input-class="'w-full'"
            :placeholder="placeholder"
            :disabled="disabled"
        />

        <div class="mt-1" v-if="error">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ error }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import DatePicker from 'primevue/datepicker';

const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    modelValue: {
        type: [String, Date, null],
        default: null,
    },
    wrapperClass: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
        required: false,
    },
    error: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Select date',
    },
});

const emit = defineEmits(['update:modelValue']);
const localValue = ref(props.modelValue);

watch(
    () => props.modelValue,
    newVal => {
        localValue.value = newVal;
    }
);

watch(localValue, newVal => {
    emit('update:modelValue', newVal);
});
</script>
