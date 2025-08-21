<script setup>
import { defineProps, ref } from 'vue';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    tabs: {
        required: true,
        type: Array,
    },
});
</script>

<template>
    <Tabs value="0" scrollable>
        <TabList
            :pt="{
                tabList:
                    'bg-slate-50! dark:bg-gray-900! rounded-md! border-none! !focus:ring-indigo-500',
                activeBar:
                    'bg-slate-50!  dark:bg-gray-900! rounded-md! border-none!',
                nextButton: 'bg-slate-50! dark:bg-gray-900!',
                prevButton: 'bg-slate-50! dark:bg-gray-900!',
            }"
        >
            <Tab
                v-for="tab in tabs"
                :key="tab.title"
                :value="tab.value"
                :pt="{
                    root: ({ context }) => ({
                        class: [
                            'border-none!',
                            context?.active
                                ? 'bg-indigo-600! text-white! !dark:bg-indigo-700 !dark:text-white'
                                : 'bg-gray-200! text-gray-700! !dark:bg-gray-800 !dark:text-gray-400',
                            '!hover:bg-gray-100 !dark:hover:bg-gray-700',
                            'rounded-md! mx-2! p-2! me-2!',
                            'transition-all! duration-300! ease-in-out!',
                        ],
                    }),
                }"
            >
                <div class="flex items-center">
                    <template v-if="tab?.link">
                        <Link :href="tab?.link" class="flex items-center">
                            <CommonIcon
                                v-if="tab?.icon"
                                class="mr-2 h-6 w-6"
                                :icon="tab?.icon"
                            />
                            {{ tab?.title }}
                        </Link>
                    </template>
                    <template v-else>
                        <div class="flex items-center">
                            <CommonIcon
                                v-if="tab.icon"
                                class="mr-2 h-6 w-6"
                                :icon="tab.icon"
                            />
                            {{ tab.title }}
                        </div>
                    </template>
                </div>
            </Tab>
        </TabList>

        <TabPanels
            :pt="{
                root: 'bg-slate-50! dark:bg-gray-900! p-2! py-5! ',
            }"
        >
            <TabPanel v-for="tab in tabs" :key="tab.content" :value="tab.value">
                <slot :name="tab.id" />
            </TabPanel>
        </TabPanels>
    </Tabs>
</template>
