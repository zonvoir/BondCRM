<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm } from '@inertiajs/vue3';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonToggleSwitch from '@/Components/Common/CommonToggleSwitch.vue';

const props = defineProps({
    menuSettings: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
    },
});

const socialiteSettings = props.data;

const form = useForm({
    microsoftClientId: socialiteSettings?.microsoft?.data?.client_id || '',
    microsoftClientSecret:
        socialiteSettings?.microsoft?.data?.client_secret || '',
    microsoftRedirect:
        socialiteSettings?.microsoft?.data?.redirect_url ||
        route('auth.microsoft.callback'),
    statusMicrosoft: socialiteSettings?.microsoft?.data?.status || null,
});

const submit = () => {
    form.post(route('settings.socialite.microsoft'), {
        onSuccess: () => {
            // Optional: success logic
        },
    });
};
</script>

<template>
    <AppLayout :title="data?.title">
        <PanelLayout>
            <SettingsLayout :menu="menuSettings">
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
                            <CommonToggleSwitch
                                v-model="form.statusMicrosoft"
                            />
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
            </SettingsLayout>
        </PanelLayout>
    </AppLayout>
</template>
