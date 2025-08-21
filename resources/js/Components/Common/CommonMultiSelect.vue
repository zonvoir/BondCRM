<script setup>
import { ref } from 'vue';
import MultiSelect from 'primevue/multiselect';

const props = defineProps({
    label: {
        type: String,
        default: '',
        required: false,
    },
    required: {
        type: Boolean,
        default: false,
        required: false,
    },
    modelValue: {
        type: Array,
        default: () => [],
    },
    options: {
        type: Array,
        required: true,
    },
    optionLabel: {
        type: String,
        required: true,
    },
    filter: {
        type: Boolean,
        default: true,
    },
    placeholder: {
        type: String,
        default: 'Select',
    },
    maxSelectedLabels: {
        type: Number,
        default: 3,
    },
    error: {
        type: String,
        default: '',
        required: false,
    },
});

const emit = defineEmits(['update:modelValue']);
const modelValue = ref(props.modelValue);

const handleUpdate = value => {
    modelValue.value = value;
    emit('update:modelValue', value);
};
</script>
<template>
    <div>
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

        <div class="card flex justify-center">
            <MultiSelect
                :pt="{
                    class: 'bg-dark!',
                }"
                v-model="modelValue"
                :options="options"
                :optionLabel="optionLabel"
                :filter="filter"
                :placeholder="placeholder"
                :maxSelectedLabels="maxSelectedLabels"
                class="w-full"
                @update:modelValue="handleUpdate"
            />
        </div>

        <div class="mt-1" v-show="error">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ error }}
            </p>
        </div>
    </div>
</template>
