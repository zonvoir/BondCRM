<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    navigation: {
        type: Object,
        required: true,
    },
    linkClass: {
        type: String,
        required: false,
    },
});
</script>
<template>
    <Link
        v-for="item in navigation"
        :key="item.name"
        :href="item.href"
        :class="linkClass"
    >
        {{ item.name }}
    </Link>
</template>
