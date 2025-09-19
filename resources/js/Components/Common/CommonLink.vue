<template>
    <Link :href="href"
        :class="[
            active
                ? activeClass
                : inActiveClass,
            'group flex !w-full !cursor-pointer gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold hover:bg-gray-200 dark:hover:bg-gray-800', customClass]">
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
    activeClass: {
        type: String,
        default: "bg-gray-50 text-indigo-600 dark:bg-gray-800"
    },
    inActiveClass: {
        type: String,
        default: 'text-gray-700 hover:text-indigo-600'
    },
    customClass: {
        type: String,
        default: ""
    }
});
</script>
