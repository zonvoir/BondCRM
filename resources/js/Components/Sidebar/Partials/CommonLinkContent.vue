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
                ? 'text-light'
                : 'text-secondary-50 group-hover:text-light',
            'h-6 w-6 shrink-0',
        ]"
        :icon="menu?.icon"
    />

    <div class="flex w-full justify-between">
        <div class="group-hover:text-light dark:text-light">
            {{ menu?.name }}
        </div>
        <div>
            <CommonIcon
                v-if="menu?.subMenu?.length > 0"
                :icon="
                    'heroicons-solid:' +
                    (activeSubmenu === menu.name
                        ? 'chevron-down'
                        : 'chevron-right')
                "
                :class="[
                    menu.active
                        ? 'text-light'
                        : 'h-6 w-6 shrink-0 text-light',
                    'h-6 w-6 shrink-0 group-hover:text-light/20',
                ]"
            />
        </div>
    </div>
</template>
