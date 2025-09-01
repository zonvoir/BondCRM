<script setup>
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const navigationMenu = [
    {
        category: 'General',
        items: [
            {
                name: 'General',
                icon: 'heroicons:cog-6-tooth',
                href: '#',
                active: true,
            },
            {
                name: 'Company Information',
                icon: 'heroicons:bars-3-bottom-right',
                href: '#',
                hasNotification: false,
            },
            {
                name: 'Localization',
                icon: 'heroicons:globe-alt',
                href: '#',
                hasNotification: false,
            },
            {
                name: 'Email',
                icon: 'heroicons:envelope',
                href: '#',
                hasNotification: false,
            },
        ],
    },
    {
        category: 'Finance',
        items: [
            {
                name: 'General',
                icon: 'heroicons:cog-6-tooth',
                href: '#',
            },
        ],
    },
    {
        category: 'Configure Features',
        items: [
            {
                name: 'Leads',
                icon: 'heroicons:document-currency-dollar',
                href: '#',
            },
        ],
    },
    {
        category: 'Integrations',
        items: [
            {
                name: 'Google',
                icon: 'heroicons:magnifying-glass-circle',
                href: '#',
            },
            {
                name: 'Zonvoir.com',
                icon: 'heroicons:globe-alt',
                href: '#',
            },
        ],
    },
    {
        category: 'AI Integration',
        items: [
            {
                name: 'General',
                icon: 'heroicons:cog-6-tooth',
                href: '#',
            },
            {
                name: 'Open AI',
                icon: 'heroicons:at-symbol',
                href: '#',
            },
        ],
    },
];
</script>
<template>
    <div class="container mx-auto">
        <div class="flex flex-col gap-3 lg:flex-row">
            <div class="w-full lg:max-w-sm xl:max-w-[280px]">
                <CommonCard
                    class="dark:bg-dark hover:shadow-3xl rounded-2xl border-0 bg-white/90 shadow-2xl backdrop-blur-sm transition-all duration-300"
                >
                    <div class="">
                        <nav class="space-y-2">
                            <div
                                v-for="section in navigationMenu"
                                :key="section.category"
                                class="mb-6"
                            >
                                <h4
                                    class="mb-3 px-1 text-xs font-semibold tracking-wider text-gray-400 uppercase"
                                >
                                    {{ section.category }}
                                </h4>

                                <div class="space-y-0">
                                    <a
                                        v-for="item in section.items"
                                        :key="item.name"
                                        :href="item.href"
                                        :class="'group flex items-center gap-3 rounded-xl px-1 py-1 text-sm font-medium transition-all duration-200 hover:bg-indigo-100 dark:hover:bg-gray-700'"
                                    >
                                        <div
                                            :class="[
                                                'flex h-7 w-7 items-center justify-center rounded-lg transition-colors',
                                                item.active
                                                    ? 'dark:bg-dark bg-indigo-100 group-hover:bg-indigo-200 dark:text-white'
                                                    : 'dark:bg-dark bg-gray-100 group-hover:bg-indigo-100 dark:text-white',
                                            ]"
                                        >
                                            <CommonIcon
                                                :icon="item.icon"
                                                :class="[
                                                    'h-4 w-4 transition-colors',
                                                    item.active
                                                        ? 'text-indigo-600'
                                                        : 'text-gray-600 group-hover:text-indigo-600',
                                                ]"
                                            />
                                        </div>

                                        <span
                                            :class="[
                                                'flex-1 text-[13px] font-medium',
                                                item.active
                                                    ? 'text-indigo-600'
                                                    : '',
                                            ]"
                                            >{{ item.name }}</span
                                        >

                                        <div
                                            v-if="item.status"
                                            class="flex items-center gap-2"
                                        >
                                            <span
                                                :class="[
                                                    'rounded-full px-2 py-1 text-xs font-medium',
                                                    item.statusColor === 'green'
                                                        ? 'bg-green-100 text-green-700'
                                                        : 'bg-gray-100 text-gray-700',
                                                ]"
                                            >
                                                {{ item.status }}
                                            </span>
                                        </div>

                                        <div
                                            v-else-if="item.hasNotification"
                                            class="flex items-center gap-2"
                                        >
                                            <span
                                                class="h-2 w-2 animate-pulse rounded-full bg-red-500"
                                            ></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </nav>
                    </div>
                </CommonCard>
            </div>

            <CommonCard
                class="sticky top-16 max-h-fit w-full rounded-2xl border-0 bg-white/90 shadow-2xl backdrop-blur-sm"
            >
                <slot />
            </CommonCard>
        </div>
    </div>
</template>
