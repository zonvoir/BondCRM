<template>
    <div :class="['rounded-md p-3 sm:p-5 shadow-sm', customClass]">
        <slot />
    </div>
</template>

<script setup>
const props = defineProps({
    customClass: {
        type: String,
        default: 'bg-white dark:bg-gray-800',
    },
})
</script>
