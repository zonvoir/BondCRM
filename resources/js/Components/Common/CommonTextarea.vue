<script setup>
const props = defineProps({
    label: {
        type: String,
        default: '',
        required: false,
    },
    labelClass: {
        type: String,
        default: '',
        required: false,
    },
    rows: {
        type: Number,
        default: 3,
        required: false,
    },
    placeholder: {
        type: String,
        default: '',
        required: false,
    },
    readonly: {
        type: Boolean,
        default: false,
        required: false,
    },
    required: {
        type: Boolean,
        default: false,
        required: false,
    },
    disabled: {
        type: Boolean,
        default: false,
        required: false,
    },
    error: {
        type: String,
        default: '',
        required: false,
    },
    TextareaClass: {
        type: String,
        default: 'w-full',
        required: false,
    },
});

const model = defineModel({
    type: String,
    required: true,
});
</script>

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

    <textarea
        ref="textarea"
        :rows="rows"
        :placeholder="placeholder"
        :readonly="readonly"
        :disabled="disabled"
        :required="required"
        v-model="model"
        :class="[
            TextareaClass,
            'w-full rounded-md border-gray-300 text-gray-900 shadow-xs focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600',
        ]"
    ></textarea>

    <div v-show="error">
        <p class="text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>
