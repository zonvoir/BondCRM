<script setup>
import { onMounted, ref } from 'vue';

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
    type: {
        type: String,
        default: 'text',
        validator: value => ['text', 'email', 'password'].includes(value),
    },
    maxlength: {
        type: Number,
        default: 280,
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
    autocomplete: {
        type: String,
        default: 'off',
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
    autofocus: {
        type: Boolean,
        default: false,
        required: false,
    },
    error: {
        type: String,
        default: '',
        required: false,
    },
    InputClass: {
        type: String,
        default: 'w-full',
        required: false,
    },
});

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
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

    <input
        ref="input"
        :type="type"
        :maxlength="maxlength"
        :placeholder="placeholder"
        :readonly="readonly"
        :disabled="disabled"
        :required="required"
        v-model="model"
        :autocomplete="autocomplete"
        :autofocus="autofocus"
        :class="[
            InputClass,
            'rounded-md border-gray-300 text-gray-900 shadow-xs focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600',
        ]"
    />

    <div class="mt-1" v-show="error">
        <p class="text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>
