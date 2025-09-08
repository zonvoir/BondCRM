<script setup>
import { SelectButton } from 'primevue';
import { ref, defineProps, defineEmits, computed } from 'vue';

const props = defineProps({
    modelValue: [String, Number, Array, Object],
    options: {
        type: Array,
        required: true,
    },
    optionLabel: {
        type: String,
        default: null,
    },
    optionValue: {
        type: String,
        default: null,
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    invalid: {
        type: Boolean,
        default: false,
    },
    ariaLabelledby: {
        type: String,
        default: '',
    },
    allowEmpty: {
        type: Boolean,
        default: true,
    },
});

const sizeClass = computed(() => {
    if (props.size === 'small') return 'p-1 text-sm';
    if (props.size === 'large') return 'p-3 text-lg';
    return '';
});
const emit = defineEmits(['update:modelValue']);

function updateValue(val) {
    emit('update:modelValue', val);
}
</script>

<template>
    <SelectButton
        v-model="props.modelValue"
        :options="props.options"
        :option-label="props.optionLabel"
        :option-value="props.optionValue"
        :multiple="props.multiple"
        :disabled="props.disabled"
        :invalid="props.invalid"
        :aria-labelledby="props.ariaLabelledby"
        :allow-empty="props.allowEmpty"
        :class="sizeClass"
        @update:modelValue="updateValue"
    >
        <template #option="slotProps">
            <slot name="option" v-bind="slotProps">{{
                slotProps.option[props.optionLabel] || slotProps.option
            }}</slot>
        </template>
    </SelectButton>
</template>
