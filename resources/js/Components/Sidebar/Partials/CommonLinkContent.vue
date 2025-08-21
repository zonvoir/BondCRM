<script setup>
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    menu: {
        type: Object,
        required: true,
    },
    activeSubmenu: {
        default: null,
        required: true,
    },
});
</script>
<template>
    <CommonIcon
        :class="[
            menu?.active
                ? 'text-indigo-600'
                : 'text-gray-400 group-hover:text-indigo-600',
            'h-6 w-6 shrink-0',
        ]"
        :icon="menu?.icon"
    />

    <div class="flex w-full justify-between">
        <div class="group-hover:text-indigo-600 dark:text-white">
            {{ menu?.name }}
        </div>
        <div>
            <CommonIcon
                v-if="menu?.subMenu?.length > 0 && menu?.subMenu?.permission"
                :icon="
                    'heroicons-solid:' +
                    (activeSubmenu === menu.name
                        ? 'chevron-down'
                        : 'chevron-right')
                "
                :class="[
                    menu.active
                        ? 'text-indigo-600'
                        : 'h-6 w-6 shrink-0 text-gray-400',
                    'h-6 w-6 shrink-0 group-hover:text-indigo-600',
                ]"
            />
        </div>
    </div>
</template>
