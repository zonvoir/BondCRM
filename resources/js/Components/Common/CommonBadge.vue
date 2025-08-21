<template>
    <Badge :value="value" :severity="severity" :class="badgeClass" />
</template>

<script setup>
import { defineProps, computed } from 'vue';
import Badge from 'primevue/badge';

const props = defineProps({
    value: {
        type: [String, Number],
        required: true,
    },
    severity: {
        type: String,
        default: 'primary',
    },
    class: {
        type: [String, Array, Object],
        default: '',
    },
});

const badgeClass = computed(() => props.class);
</script>
