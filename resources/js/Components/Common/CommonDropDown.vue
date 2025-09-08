<script setup>
import { Menu } from 'primevue';
import { ref } from 'vue';

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
});

const menuRef = ref();

defineExpose({
    toggle: event => menuRef.value.toggle(event),
    show: event => menuRef.value.show(event),
    hide: () => menuRef.value.hide(),
});
</script>

<template>
    <Menu ref="menuRef" :model="items" popup />
</template>
