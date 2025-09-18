<template>
    <div
        class="rounded-lg border border-gray-200 bg-white p-3  transition hover:shadow-lg dark:border-gray-600 dark:bg-gray-800">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <!-- <CommonAvatar label="KT" class="!bg-indigo-100 !text-xs !text-indigo-700" size="small" shape="circle" /> -->
            <div>
                <h1 class="text-sm font-semibold text-gray-900 dark:text-gray-100 cursor-pointer hover:underline">
                    #1 – {{ data.name }} <span class="font-normal text-gray-600 dark:text-gray-400">PhD</span>
                </h1>
            </div>
        </div>

        <!-- Divider -->
        <div class="my-4 h-px bg-gray-100 dark:bg-gray-600"></div>

        <!-- Content -->
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 dark:bg-gray-700">
                        <CommonIcon icon="heroicons-outline:home" class="w-4 h-4 text-gray-700 dark:text-gray-300" />
                    </span>
                    <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300">Source</h5>
                </div>
                <h4 class="text-xs font-medium text-gray-900 dark:text-gray-100">Google</h4>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 dark:bg-gray-700">
                        <CommonIcon icon="heroicons-outline:currency-dollar"
                            class="w-4 h-4 text-gray-700 dark:text-gray-300" />
                    </span>
                    <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300">Lead Value</h5>
                </div>
                <h4 class="text-xs font-medium text-gray-900 dark:text-gray-100">--</h4>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-md bg-gray-100 dark:bg-gray-700">
                        <CommonIcon icon="heroicons-outline:calendar"
                            class="w-4 h-4 text-gray-700 dark:text-gray-300" />
                    </span>
                    <h5 class="text-xs font-medium text-gray-700 dark:text-gray-300">Last Contacted</h5>
                </div>
                <h4 class="text-xs font-medium text-gray-900 dark:text-gray-100">an hour Ago</h4>
            </div>

            <!-- Divider -->
            <div class="my-4 h-px bg-gray-100 dark:bg-gray-600"></div>

            <div class="flex items-center justify-between px-2">
                <h4 class="text-xs font-medium text-gray-700 dark:text-gray-100">an hour Ago</h4>
                <div class="flex items-center gap-2">
                    <CommonIcon icon="heroicons:newspaper" />
                    <CommonIcon icon="heroicons:paper-clip" />
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import CommonIcon from './CommonIcon.vue';


defineProps({
    data: {
        type: Object,
        required: true,
        default: () => ({
            name: '',
            email: '',
            amount: 0
        })
    }
})
</script>
