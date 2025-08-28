<script setup>
import { useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonToggleSwitch from '@/Components/Common/CommonToggleSwitch.vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';

const props = defineProps({
    socialiteSettings: {
        required: true,
    },
});

const form = useForm({
    microsoftClientId:
        props?.socialiteSettings?.microsoft?.data?.client_id || '',
    microsoftClientSecret:
        props?.socialiteSettings?.microsoft?.data?.client_secret || '',
    microsoftRedirect:
        props?.socialiteSettings?.microsoft?.data?.redirect_url ||
        route('auth.microsoft.callback'),

    googleClientId: props?.socialiteSettings?.google?.data?.client_id || '',
    googleClientSecret:
        props?.socialiteSettings?.google?.data?.client_secret || '',
    googleRedirect:
        props?.socialiteSettings?.google?.data?.redirect_url ||
        route('auth.google.callback'),

    statusMicrosoft: props?.socialiteSettings?.microsoft?.data?.status || null,
    statusGoogle: props?.socialiteSettings?.google?.data?.status || null,
});

const submit = () => {
    form.post(route('settings.socialite'), {
        onSuccess: () => {
            // Optional: success logic
        },
    });
};
</script>

<template>
    <AppLayout title="Settings">
        <PanelLayout>
            <form @submit.prevent="submit" class="space-y-8 rounded-xl">
                <!-- LinkedIn Settings -->
                <CommonCard class="rounded-lg p-6 shadow-xs">
                    <div
                        class="mb-4 flex w-full items-center justify-between border-b pb-2 text-lg font-semibold text-gray-800"
                    >
                        <div class="flex items-center">
                            <CommonIcon
                                icon="logos:microsoft-icon"
                                class="mr-2 text-blue-600"
                            />
                            <span class="text-black dark:text-white"
                                >Microsoft Azure</span
                            >
                        </div>
                        <CommonToggleSwitch v-model="form.statusMicrosoft" />
                    </div>
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-1">
                        <CommonInput
                            label="Microsoft Client ID"
                            v-model="form.microsoftClientId"
                            :error="form.errors.microsoftClientId"
                        />
                        <CommonInput
                            label="Microsoft Client Secret"
                            v-model="form.microsoftClientSecret"
                            :error="form.errors.microsoftClientSecret"
                        />
                        <CommonInput
                            type="url"
                            readonly
                            label="Microsoft Redirect URL"
                            v-model="form.microsoftRedirect"
                            :error="form.errors.microsoftRedirect"
                        />
                    </div>
                </CommonCard>

                <!-- Google Settings -->
                <CommonCard class="rounded-lg p-6 shadow-xs">
                    <div
                        class="mb-4 flex w-full items-center justify-between border-b pb-2 text-lg font-semibold text-gray-800"
                    >
                        <div class="flex items-center">
                            <CommonIcon
                                icon="logos:google-icon"
                                class="mr-2 text-red-500"
                            />
                            <span class="text-black dark:text-white"
                                >Google</span
                            >
                        </div>
                        <CommonToggleSwitch v-model="form.statusGoogle" />
                    </div>
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-1">
                        <CommonInput
                            label="Google Client ID"
                            v-model="form.googleClientId"
                            :error="form.errors.googleClientId"
                        />
                        <CommonInput
                            label="Google Client Secret"
                            v-model="form.googleClientSecret"
                            :error="form.errors.googleClientSecret"
                        />
                        <CommonInput
                            type="url"
                            readonly
                            label="Google Redirect URL"
                            v-model="form.googleRedirect"
                            :error="form.errors.googleRedirect"
                        />
                    </div>
                </CommonCard>

                <!-- Save Button -->
                <div class="flex justify-start">
                    <CommonButton
                        :processing="form.processing"
                        variant="primary"
                        type="submit"
                        class="px-6 py-2"
                    >
                        Save Changes
                    </CommonButton>
                </div>
            </form>
        </PanelLayout>
    </AppLayout>
</template>
