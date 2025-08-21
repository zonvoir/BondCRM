<template>
    <div class="flex flex-col">
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

        <div class="mt-2 flex flex-wrap gap-4">
            <div
                v-for="option in options"
                :key="option.value"
                class="flex items-center"
            >
                <RadioButton
                    :inputId="option.value"
                    :name="name"
                    :value="option.value"
                    :disabled="disabled"
                    :model-value="modelValue"
                    @update:model-value="updateModelValue"
                />
                <label :for="option.value" class="ml-2">
                    {{ option.label }}
                </label>
            </div>
        </div>

        <div v-show="error">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ error }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import RadioButton from 'primevue/radiobutton';

const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    labelClass: {
        type: String,
        default: '',
    },
    options: {
        type: Array,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    required: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue']);
const updateModelValue = value => {
    emit('update:modelValue', value);
};
</script>
