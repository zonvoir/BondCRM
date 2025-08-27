<template>
    <Editor
        v-model="localValue"
        :editorStyle="editorStyle"
        :readonly="readonly"
        @text-change="handleTextChange"
    />
</template>

<script setup>
import Editor from 'primevue/editor';
import { defineEmits, defineProps, watch, ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    editorStyle: {
        type: String,
        default: 'height: 320px',
    },
    readonly: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue', 'textChange']);
const localValue = ref(props.modelValue);

watch(
    () => props.modelValue,
    val => {
        if (val !== localValue.value) {
            localValue.value = val;
        }
    }
);

const handleTextChange = event => {
    emit('update:modelValue', event.htmlValue);
    emit('textChange', event);
};
</script>
<style scoped>
:deep(.ql-toolbar .ql-image) {
    display: none !important;
}

:deep(.ql-toolbar .ql-video) {
    display: none !important;
}
</style>
