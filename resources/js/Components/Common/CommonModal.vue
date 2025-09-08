<template>
    <Dialog
        :draggable="false"
        :visible="visible"
        @update:visible="emit('update:visible', $event)"
        maximizable
        modal
        :header="title"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        :position="position"
        :pt="{
            root: {
                class: className,
            },
            mask: {
                class: 'backdrop-blur-sm bg-black/40',
            },
        }"
    >
        <slot></slot>
    </Dialog>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';

const props = defineProps({
    visible: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Header',
    },
    position: {
        type: String,
        default: 'center',
    },
    className: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:visible']);
</script>
