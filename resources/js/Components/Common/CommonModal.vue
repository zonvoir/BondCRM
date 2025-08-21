<template>
    <Dialog
        :draggable="false"
        :visible="visible"
        @update:visible="emit('update:visible', $event)"
        maximizable
        modal
        :header="title"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <slot></slot>
    </Dialog>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';

const props = defineProps({
    visible: Boolean,
    title: {
        type: String,
        default: 'Header',
    },
});

const emit = defineEmits(['update:visible']);
</script>
