<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonToggleSwitch from '@/Components/Common/CommonToggleSwitch.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import { useForm } from '@inertiajs/vue3';

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
    googleClientId: socialiteSettings?.google?.data?.client_id || '',
    googleClientSecret: socialiteSettings?.google?.data?.client_secret || '',
    googleRedirect:
        socialiteSettings?.google?.data?.redirect_url ||
        route('auth.google.callback'),
    statusGoogle: socialiteSettings?.google?.data?.status || null,
});

const submit = () => {
    form.post(route('settings.socialite.google'), {
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
            </SettingsLayout>
        </PanelLayout>
    </AppLayout>
</template>
