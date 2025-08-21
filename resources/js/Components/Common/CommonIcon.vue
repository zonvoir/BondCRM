<script setup>
/*
 * This component is a wrapper around the Iconify Vue component.
 * You can use this component to display icons from the Iconify library.
 *
 * https://icones.js.org/
 */
import { Icon } from '@iconify/vue';

defineProps({
    icon: {
        required: true,
        type: String,
    },
});
</script>

<template>
    <Icon :icon="icon" :ssr="false" />
</template>
