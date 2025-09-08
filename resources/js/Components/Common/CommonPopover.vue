<script setup>
import Popover from 'primevue/popover';
import { ref } from 'vue';

const popover = ref(null);
defineExpose({ popover });
</script>

<template>
    <Popover ref="popover" draggable="true">
        <slot />
    </Popover>
</template>
