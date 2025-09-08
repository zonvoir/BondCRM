<template>
    <Drawer
        :visible="visible"
        :header="header"
        :position="position"
        :style="{ ...drawerStyle, width }"
        :pt="{
            mask: {
                class: 'backdrop-blur-xs bg-black/20',
            },
        }"
    >
        <!-- Header Slot -->
        <template #header>
            <slot name="header">
                <span class="text-xl font-bold">{{ header }}</span>
            </slot>
        </template>

        <template #closeicon>
            <CommonIcon
                class="cursor-pointer"
                @click="handleClose"
                icon="heroicons-outline:x-mark"
            />
        </template>

        <!-- Default Slot -->
        <slot>
            {{ content }}
        </slot>

        <!-- Footer Slot -->
        <template #footer>
            <slot name="footer"></slot>
        </template>
    </Drawer>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import Drawer from 'primevue/drawer';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

defineProps({
    visible: {
        type: Boolean,
        required: true,
    },
    header: {
        type: String,
        default: 'Hello Drawer',
    },
    position: {
        type: String,
        default: 'right', // Options: "left", "right", "top", "bottom"
    },
    drawerStyle: {
        type: Object,
        default: () => ({}),
    },
    content: {
        type: String,
        default: 'Default content.',
    },
    width: {
        type: String,
        default: '500px',
    },
});

const emit = defineEmits(['update:visible']);

const handleClose = () => {
    emit('update:visible', false);
};
</script>
