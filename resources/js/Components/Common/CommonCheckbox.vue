<template>
    <div class="card flex justify-center">
        <Checkbox v-model="internalValue" binary />
    </div>
</template>

<script setup>
import { computed } from 'vue';
import Checkbox from 'primevue/checkbox';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const internalValue = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
});
</script>
