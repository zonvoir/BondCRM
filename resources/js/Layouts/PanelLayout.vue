<script setup>
import SideMenu from '@/Components/Sidebar/SideMenu.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    title: {
        type: String,
        required: false,
    },
});
</script>
<template>
    <AppLayout :title="title">
        <SideMenu>
            <main class="bg-light dark:bg-dark min-h-screen py-3">
                <div class="px-4 sm:px-6 lg:px-3">
                    <slot />
                </div>
            </main>
        </SideMenu>
    </AppLayout>
</template>
