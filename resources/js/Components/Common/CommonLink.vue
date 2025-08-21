<template>
    <Link
        :href="href"
        :class="[
            active
                ? 'bg-gray-50 text-indigo-600 dark:bg-gray-800'
                : 'text-gray-700 hover:text-indigo-600',
            'group flex !cursor-pointer gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold hover:bg-gray-200 dark:hover:bg-gray-800',
        ]"
    >
        <slot></slot>
    </Link>
</template>

<script setup>
defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
        default: false,
    },
});
</script>
