<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    configurations: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <AppLayout title="Settings">
        <PanelLayout>
            <div class="grid grid-cols-4 gap-0">
                <div
                    v-for="(configuration, key, index) in configurations"
                    :key="index"
                    class="px-4"
                >
                    <div
                        :class="[
                            'rounded-lg p-6 shadow-sm sm:p-5 dark:bg-gray-800',
                            configuration?.color,
                        ]"
                    >
                        <a :href="configuration?.routeName">
                            <h3 class="mb-2 font-semibold text-gray-600">
                                SYNC WITH
                            </h3>
                            <div class="flex items-center gap-3">
                                <img
                                    :src="configuration?.icon"
                                    alt="Outlook"
                                    class="h-8 w-8"
                                />
                                <span class="font-semibold">{{ key }}</span>
                            </div>
                            <div
                                class="mt-4 rounded bg-green-500 px-3 py-1 text-sm text-white"
                            >
                                {{ configuration?.email }}
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </PanelLayout>
    </AppLayout>
</template>
