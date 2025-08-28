<script setup>
import { defineProps, defineEmits } from 'vue';
import Select from 'primevue/select';

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        default: null,
    },
    options: {
        type: Array,
        required: true,
    },
    optionLabel: {
        type: String,
        default: 'name',
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },
    label: {
        type: String,
        default: '',
    },
    labelClass: {
        type: String,
        default: '',
    },
    selectClass: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const updateModelValue = value => {
    emit('update:modelValue', value);
    emit('change', value);
};
</script>
<template>
    <div class="flex flex-col">
        <label
            :class="[
                labelClass,
                'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300',
            ]"
            v-if="label"
        >
            <span class="flex items-center">
                {{ label }}
                <p class="mx-1 text-red-600" v-if="required">*</p>
            </span>
        </label>
        <Select
            :model-value="modelValue"
            :options="options"
            filter
            :optionLabel="optionLabel"
            :placeholder="placeholder"
            :class="[selectClass, 'h-10 w-full']"
            @update:model-value="updateModelValue"
        >
            <template #value="slotProps">
                <div v-if="slotProps.value" class="flex items-center">
                    <div>{{ slotProps.value[optionLabel] }}</div>
                </div>
                <span v-else>
                    {{ placeholder }}
                </span>
            </template>
            <template #option="slotProps">
                <div class="flex items-center">
                    <div>{{ slotProps.option[optionLabel] }}</div>
                </div>
            </template>
        </Select>
        <div class="mt-1" v-show="error">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ error }}
            </p>
        </div>
    </div>
</template>
