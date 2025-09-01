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
import InboxOutlookDetails from '@/Pages/Mails/Partials/InboxOutlookDetails.vue';
import InboxGmailDetails from '@/Pages/Mails/Partials/InboxGmailDetails.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';

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

const message = ref(null);

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

const viewInbox = message_id => {
    axios
        .post(route('employee.gmail.view'), { id: message_id })
        .then(response => {
            if (response) {
                message.value = response?.data;
            }
        })
        .catch(error => { });
};

const clearMessage = () => {
    message.value = null;
};

const selectedItem = row => {
    viewInbox(row?.data?.id);
};

const selectRows = e => {
    console.log(e);
};
</script>

<template>
    <AppLayout title="Gmail">
        <PanelLayout>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
                <div class="col-span-1 md:col-span-3 relative ">
                    <div class="sticky top-20 ">
                        <CommonSidebarMail route-name="employee.gmail" :sideList="sideList" />
                    </div>
                </div>

                <CommonCard class="col-span-1 md:col-span-9 relative">
                    <div v-if="message">
                        <div class="flex flex-col items-end ">
                            <CommonIcon @click="clearMessage" class="h-8 w-8 cursor-pointer"
                                icon="material-symbols:close" />
                        </div>
                        <InboxGmailDetails :message="message" />
                    </div>
                    <div v-else class="flex flex-col items-center gap-3 py-1">
                        <div class="flex w-full justify-between">
                            <div class="flex items-center gap-2">
                                <Link class="cursor-pointer rounded-lg bg-gray-50 p-2">
                                <CommonIcon class="h-7 w-7 text-gray-500" icon="mdi:sync-circle" />
                                </Link>

                                <CommonButton size="xs">
                                    Goto Leads
                                </CommonButton>
                            </div>

                            <!-- Spacer -->
                            <div class="ml-auto" />

                            <!-- Right: Search -->
                            <form class="relative w-full max-w-[320px]" role="search" aria-label="Search inbox">
                                <CommonInput  icon="material-symbols:search-rounded" placeholder="Search" type="search"/>
                            </form>
                        </div>
                        <div class="flex w-full justify-start">
                            <CommonDataTable :showSerialNumber="false" :data="mails" :otherArgument="pageToken"
                                :checkbox="true" @rowClick="selectedItem" @update:modelSelection="selectRows">
                                <Column field="name" header="" :sortable="false">
                                    <template #body="slotProps">
                                        <div class="flex cursor-pointer gap-2">
                                            <CommonIcon :class="[
                                                'h-7 w-7',
                                                slotProps?.data?.is_starred
                                                    ? 'text-yellow-400'
                                                    : 'text-gray-400',
                                            ]" :icon="slotProps?.data?.is_starred
                                                ? 'material-symbols:star-rate-rounded'
                                                : 'material-symbols-light:star-outline-rounded'
                                                " />
                                        </div>
                                    </template>
                                </Column>

                                <Column field="name" header="" :sortable="false">
                                    <template #body="slotProps">
                                        <div class="flex cursor-pointer gap-2">
                                            <img class="h-7 w-7" v-if="
                                                slotProps?.data?.from
                                                    ?.avatar?.exists
                                            " :src="slotProps?.data?.from
                                                ?.avatar?.url
                                                " alt="logo" />
                                            <span class="text-nowrap">
                                                {{
                                                    slotProps?.data?.from?.name
                                                }}</span>
                                        </div>
                                    </template>
                                </Column>

                                <Column field="subject" header="" :sortable="false" />
                                <Column field="created_at" header="" :sortable="false" />
                            </CommonDataTable>
                        </div>
                    </div>
                </CommonCard>
            </div>
        </PanelLayout>
    </AppLayout>
</template>

<style scoped></style>
