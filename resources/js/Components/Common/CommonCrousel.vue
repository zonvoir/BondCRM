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
    nextButtonClass: {
        type: String,
        default: ""
    },
    prevButtonClass: {
        type: String,
        default: ""
    }
});
</script>

<template>
    <Carousel class="w-full" :pt="{
        pcNextButton: {
            root: {
                class: nextButtonClass,
            },
        },
        pcPrevButton: {
            root: {
                class: prevButtonClass,
            },
        },
    }" v-bind="$props">
        <template #item="slotProps">
            <slot name="item" v-bind="slotProps" />
        </template>
    </Carousel>
</template>
