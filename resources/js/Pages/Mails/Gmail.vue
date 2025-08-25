<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonSidebarMail from '@/Pages/Mails/Partials/CommonSidebarMail.vue';
import CommonCheckbox from '@/Components/Common/CommonCheckbox.vue';
import { ref } from 'vue';
import Column from 'primevue/column';
import CommonDataTable from '@/Components/Common/CommonDataTable.vue';

defineProps({
    mails: {
        type: Array,
        required: true,
    },
    pageToken: {
        type: String,
        required: true,
    },
});

const sideList = ref([
    { key: 'inbox', label: 'Inbox', icon: 'material-symbols:mail', count: 201 },
    {
        key: 'starred',
        label: 'Starred',
        icon: 'material-symbols:star-half-rounded',
        count: 50,
    },
    {
        key: 'draft',
        label: 'Draft',
        icon: 'material-symbols:docs-rounded',
        count: 50,
    },
    { key: 'sent', label: 'Sent', icon: 'ic:sharp-send', count: 50 },
    { key: 'trash', label: 'Trash', icon: 'mdi:delete', count: 50 },
]);
</script>

<template>
    <AppLayout title="Gmail">
        <PanelLayout>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                <CommonSidebarMail
                    route-name="employee.gmail"
                    :sideList="sideList"
                />

                <CommonCard class="col-span-1 md:col-span-9">
                    <div class="flex flex-col items-center gap-3 py-1 sm:px-5">
                        <div class="flex w-full justify-between">
                            <div class="flex items-center gap-2">
                                <label
                                    class="inline-flex items-center justify-center"
                                >
                                    <CommonCheckbox />
                                </label>

                                <div
                                    class="cursor-pointer rounded-lg bg-gray-50 p-2"
                                >
                                    <CommonIcon
                                        class="h-7 w-7 text-gray-500"
                                        icon="mdi:sync-circle"
                                    />
                                </div>

                                <div
                                    class="cursor-pointer rounded-lg bg-gray-50 p-2"
                                >
                                    <CommonIcon
                                        class="h-7 w-7 text-gray-500"
                                        icon="mdi:delete"
                                    />
                                </div>

                                <CommonButton size="xs">
                                    Goto Leads
                                </CommonButton>
                            </div>

                            <!-- Spacer -->
                            <div class="ml-auto" />

                            <!-- Right: Search -->
                            <form
                                class="relative w-full max-w-[320px]"
                                role="search"
                                aria-label="Search inbox"
                            >
                                <span
                                    class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-neutral-400"
                                >
                                    <svg
                                        class="size-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <path
                                            stroke-linecap="round"
                                            d="M20 20l-3.5-3.5"
                                        ></path>
                                    </svg>
                                </span>

                                <input
                                    type="search"
                                    placeholder="Search inbox"
                                    class="w-full rounded-sm border-0 bg-gray-50 py-2 pr-3 pl-10 text-[15px] text-neutral-700 shadow-inner ring-1 ring-neutral-200 ring-inset placeholder:text-neutral-400 focus:ring-2 focus:ring-blue-500/60 focus:outline-none dark:bg-neutral-800 dark:text-neutral-100 dark:ring-neutral-700 dark:placeholder:text-neutral-500"
                                />
                            </form>
                        </div>
                        <div class="flex w-full justify-start">
                            <CommonDataTable
                                :showSerialNumber="false"
                                :data="mails"
                                :otherArgument="pageToken"
                            >
                                <Column
                                    field="id"
                                    header=""
                                    :sortable="false"
                                />
                                <Column
                                    field="subject"
                                    header=""
                                    :sortable="false"
                                />
                            </CommonDataTable>
                        </div>
                    </div>
                </CommonCard>
            </div>
        </PanelLayout>
    </AppLayout>
</template>

<style scoped></style>
