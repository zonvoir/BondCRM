<script setup>
import { SelectButton } from 'primevue';
import { ref, defineProps, defineEmits, computed } from 'vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    modelValue: [String, Number, Array, Object],
    options: {
        type: Array,
        required: true,
    },
    optionLabel: {
        type: String,
        default: 'label',
    },
    optionValue: {
        type: String,
        default: 'value',
    },
    multiple: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'medium',
        validator: value => ['small', 'medium', 'large'].includes(value),
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
    iconField: {
        type: String,
        default: 'icon',
    },
    showLabel: {
        type: Boolean,
        default: false,
    },
    iconPrefix: {
        type: String,
        default: 'material-symbols:',
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const internalValue = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
        emit('change', value);
    },
});

const sizeClass = computed(() => {
    const sizeMap = {
        small: 'p-1 text-sm',
        medium: 'p-2 text-base',
        large: 'p-3 text-lg',
    };
    return sizeMap[props.size] || sizeMap.medium;
});

const getIconName = iconName => {
    return props.iconPrefix + iconName;
};
</script>

<template>
    <SelectButton
        v-model="internalValue"
        :options="options"
        :option-label="optionLabel"
        :option-value="optionValue"
        :multiple="multiple"
        :disabled="disabled"
        :invalid="invalid"
        :aria-labelledby="ariaLabelledby"
        :allow-empty="allowEmpty"
        :class="sizeClass"
    >
        <template #option="{ option }">
            <div class="flex items-center gap-2">
                <CommonIcon
                    v-if="option[iconField]"
                    :icon="getIconName(option[iconField])"
                    class="h-5 w-5"
                />
                <span v-if="showLabel && option[optionLabel]">
                    {{ option[optionLabel] }}
                </span>
            </div>
        </template>
    </SelectButton>
</template>
