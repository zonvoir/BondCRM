<script setup>
import { Button, Carousel } from 'primevue';

defineProps({
    value: {
        type: Array,
        required: true,
    },
    numVisible: Number,
    numScroll: Number,
    responsiveOptions: {
        type: Array,
        default: () => [],
    },
    circular: Boolean,
    autoplayInterval: Number,
    orientation: {
        type: String,
        default: 'horizontal',
    },
});
</script>

<template>
    <Carousel class="w-full" v-bind="$props">
        <template #item="slotProps">
            <slot name="item" v-bind="slotProps" />
        </template>
    </Carousel>
</template>
