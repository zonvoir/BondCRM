<template>
    <label
        :class="[
            labelClass,
            'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300',
        ]"
    >
        <span class="flex items-center" v-if="label">
            {{ label }}
            <p class="mx-1 text-red-600" v-if="required">*</p>
        </span>
    </label>

    <ColorPicker
        v-model="innerValue"
        :disabled="disabled"
        :pt="{ input: { class: 'w-8 h-8 cursor-pointer' } }"
    />

    <div class="mt-1" v-show="error">
        <p class="text-sm text-red-600 dark:text-red-400">{{ error }}</p>
    </div>
</template>

<script setup>
import ColorPicker from 'primevue/colorpicker';
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '#ffffff',
    },
    label: {
        type: String,
        default: '',
    },
    labelClass: {
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
    },
    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const innerValue = computed({
    get: () => props.modelValue,
    set: val => emit('update:modelValue', val),
});
</script>
