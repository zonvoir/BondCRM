<script setup>
import { onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useFlashMessages } from '@/composables/useFlashMessage';
const { showFlashMessages } = useFlashMessages();

defineProps({
    title: {
        type: String,
        required: false,
    },
});
onMounted(() => {
    showFlashMessages();
});
</script>
<template>
    <Head :title="title" />
    <div>
        <slot />
    </div>
</template>
