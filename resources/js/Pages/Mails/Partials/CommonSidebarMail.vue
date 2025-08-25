<script setup>
import { ref } from 'vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import { Link } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';

defineProps({
    routeName: {
        type: String,
    },
    sideList: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div class="col-span-1 md:col-span-3">
        <CommonCard>
            <CommonButton size="sm" class="mb-5 w-full"> Compose </CommonButton>
            <aside class="w-full max-w-sm space-y-2">
                <nav class="flex flex-col gap-2">
                    <Link
                        :href="route(routeName, list.key)"
                        v-for="list in sideList"
                        :key="list.key"
                        class="group flex w-full items-center justify-between rounded-sm p-3 pr-3 pl-3 text-left transition"
                        :class="[
                            route().current(routeName, list.key)
                                ? 'bg-gray-100/80 dark:bg-gray-600'
                                : 'hover:bg-gray-100',
                        ]"
                        aria-current="page"
                    >
                        <span class="flex items-center gap-3">
                            <CommonIcon
                                :icon="list.icon"
                                class="h-6 w-6"
                                :class="
                                    route().current(routeName, list.key)
                                        ? 'text-blue-600'
                                        : 'text-gray-600'
                                "
                            />
                            <span
                                class="text-[15px] font-medium"
                                :class="
                                    route().current(routeName, list.key)
                                        ? 'text-blue-600'
                                        : 'text-gray-600'
                                "
                            >
                                {{ list.label }}
                            </span>
                        </span>

                        <span
                            v-if="typeof list.count === 'number'"
                            class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-700"
                        >
                            {{ list.count }}
                        </span>
                    </Link>
                </nav>
            </aside>
        </CommonCard>
    </div>
</template>
