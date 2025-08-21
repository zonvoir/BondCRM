<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonTabs from '@/Components/Common/CommonTabs.vue';
import GeneralSettings from '@/Pages/Settings/Partials/GeneralSettings.vue';
import EmailSettings from '@/Pages/Settings/Partials/EmailSettings.vue';
import PwaSettings from '@/Pages/Settings/Partials/PwaSettings.vue';
import LoginSettings from '@/Pages/Settings/Partials/LoginSettings.vue';
import LiveChatSettings from '@/Pages/Settings/Partials/LiveChatSettings.vue';

const props = defineProps({
    timezones: {
        type: Array,
        required: true,
    },
    generalSettings: {
        required: false,
    },
    emailSettings: {
        required: false,
    },
    socialiteSettings: {
        required: false,
    },
    liveChatSettings: {
        type: Object,
    },
});

const tabItems = [
    {
        id: 'item-1',
        title: 'General Settings',
        value: '0',
        icon: 'heroicons-solid:cog-6-tooth',
    },
    {
        id: 'item-2',
        title: 'PWA Settings',
        value: '2',
        icon: 'solar:monitor-smartphone-outline',
    },
    {
        id: 'item-3',
        title: 'Email Settings',
        value: '4',
        icon: 'solar:server-square-cloud-linear',
    },
    {
        id: 'item-4',
        title: 'Social Login Settings',
        value: '5',
        icon: 'material-symbols:web',
    },
    {
        id: 'item-5',
        title: 'Live Chat Settings',
        value: '6',
        icon: 'solar:chat-dots-linear',
    },
];
</script>

<template>
    <AppLayout title="Settings">
        <PanelLayout>
            <CommonTabs :tabs="tabItems">
                <template #item-1>
                    <GeneralSettings
                        :timezones="timezones"
                        :generalSettings="generalSettings"
                    />
                </template>
                <template #item-2>
                    <PwaSettings :generalSettings="generalSettings" />
                </template>
                <template #item-3>
                    <EmailSettings :emailSettings="emailSettings" />
                </template>
                <template #item-4>
                    <LoginSettings :socialiteSettings="socialiteSettings" />
                </template>
                <template #item-5>
                    <LiveChatSettings :liveChatSettings="liveChatSettings" />
                </template>
            </CommonTabs>
        </PanelLayout>
    </AppLayout>
</template>
