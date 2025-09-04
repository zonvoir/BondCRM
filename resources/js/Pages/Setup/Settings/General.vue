<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm } from '@inertiajs/vue3';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import { ref } from 'vue';
import { useCustomToast } from '@/Composables/useToast';
import ImageUpload from '@/Components/ImageUpload.vue';
const { showToast } = useCustomToast();

const props = defineProps({
    menuSettings: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        required: true,
    },
    generalSettings: {
        type: Object,
        default: {},
    },
});

const generalSettings = props.data.generalSettings;

const clearCache = ref(false);
const storageLink = ref(false);
const manuallyCronRun = ref(false);
const isLink = ref(generalSettings?.data?.storage_link);

const form = useForm({
    companyName: props.generalSettings?.data?.companyName ?? '',
    iconLogoDark: '',
    iconLogoWhite: '',
    logoDark: '',
    logoWhite: '',
    favicon: '',
    allowFileTypes: props.generalSettings?.data?.allowFileTypes ?? '',
});

const submit = () => {};

const clearCacheSubmit = () => {
    clearCache.value = true;
    axios
        .post(route('settings.clear.cache'))
        .then(response => {
            if (response) {
                clearCache.value = false;
                showToast('Optimized and cleared successfully!', 'success');
            }
        })
        .catch(error => {
            showToast('Error clearing cache', 'error');
        });
};
const storageLinkSubmit = () => {
    storageLink.value = true;
    isLink.value = true;
    axios
        .post(route('settings.storage.link'))
        .then(response => {
            if (response) {
                storageLink.value = false;
                showToast('Storage linked successfully!', 'success');
            }
        })
        .catch(error => {
            showToast('Failed to linked storage', 'error');
        });
};

const cronRunSubmit = () => {
    manuallyCronRun.value = true;
    axios
        .post(route('settings.run.cron'))
        .then(response => {
            if (response) {
                manuallyCronRun.value = false;
                showToast('All cron jobs run successfully!', 'success');
            }
        })
        .catch(error => {
            showToast('Failed to run cron jobs', 'error');
        });
};
</script>

<template>
    <AppLayout :title="data?.title">
        <PanelLayout>
            <SettingsLayout :menu="menuSettings">
                <form method="post">
                    <div class="flex flex-col gap-3">
                        <div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <h1
                                        class="pt-2 text-base font-semibold text-gray-600 dark:text-gray-100"
                                    >
                                        Company Info
                                    </h1>
                                    <h6 class="pb-3 text-sm text-gray-500">
                                        Provide the company information below
                                    </h6>
                                </div>
                                <div>
                                    <div class="flex justify-start py-5">
                                        <CommonButton
                                            :processing="form.processing"
                                            variant="primary"
                                            @click="submit"
                                        >
                                            Save Changes
                                        </CommonButton>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div
                                class="flex items-center justify-between gap-3 py-4"
                            >
                                <div class="w-full">
                                    <CommonInput
                                        label="Company Name"
                                        required
                                        v-model="form.companyName"
                                        :error="form.errors.companyName"
                                    />
                                </div>

                                <div class="w-full">
                                    <CommonInput
                                        label="Allowed file types"
                                        required
                                        v-model="form.allowFileTypes"
                                        :error="form.errors.allowFileTypes"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <div class="pb-4">
                                <h1
                                    class="text-base font-semibold text-gray-600 dark:text-gray-100"
                                >
                                    Company Images
                                </h1>
                                <h6 class="pb-3 text-sm text-gray-500">
                                    Provide the Company Images
                                </h6>
                                <hr />
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <ImageUpload
                                    label="Icon Logo Dark (512 x 512)"
                                    acceptedFormats="image/png"
                                    required
                                    v-model="form.iconLogoDark"
                                    :error="form.errors.iconLogoDark"
                                    :image="
                                        generalSettings?.data?.icon_logo_dark
                                    "
                                />
                                <ImageUpload
                                    label="Icon Logo White (512 x 512)"
                                    acceptedFormats="image/png"
                                    required
                                    v-model="form.iconLogoWhite"
                                    :error="form.errors.iconLogoWhite"
                                    :image="
                                        generalSettings?.data?.icon_logo_white
                                    "
                                />
                                <ImageUpload
                                    label="Logo Dark (300 x 55)"
                                    acceptedFormats="image/png"
                                    required
                                    v-model="form.logoDark"
                                    :error="form.errors.logoDark"
                                    :image="generalSettings?.data?.logo_dark"
                                />
                                <ImageUpload
                                    label="Logo White (300 x 55)"
                                    acceptedFormats="image/png"
                                    required
                                    v-model="form.logoWhite"
                                    :error="form.errors.logoWhite"
                                    :image="generalSettings?.data?.logo_white"
                                />
                                <ImageUpload
                                    label="Favicon (40 x 40)"
                                    acceptedFormats="image/png"
                                    required
                                    v-model="form.favicon"
                                    :error="form.errors.favicon"
                                    :image="generalSettings?.data?.favicon"
                                />
                            </div>
                        </div>
                    </div>
                </form>
                <div class="pt-6">
                    <div>
                        <h1
                            class="text-base font-semibold text-gray-600 dark:text-gray-100"
                        >
                            Application Utilities
                        </h1>
                        <h6 class="pb-3 text-sm text-gray-500">
                            Quick actions for cache, storage & cron
                        </h6>
                        <hr />
                    </div>
                    <form method="post">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-12 sm:col-span-4">
                                <CommonButton
                                    v-tooltip.top="
                                        'Clear the application cache and optimize the system.'
                                    "
                                    class="w-full"
                                    variant="secondary"
                                    icon="heroicons-outline:arrow-path"
                                    @click="clearCacheSubmit"
                                    :processing="clearCache"
                                >
                                    Clear Cache
                                </CommonButton>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <CommonButton
                                    v-tooltip.top="
                                        isLink
                                            ? 'Storage link created.'
                                            : 'Create a symbolic link to the storage folder. This is required for file uploads to be publicly accessible.'
                                    "
                                    class="w-full"
                                    variant="secondary"
                                    :icon="
                                        isLink
                                            ? 'heroicons-outline:circle-stack'
                                            : 'heroicons-outline:exclamation-circle'
                                    "
                                    :processing="storageLink"
                                    @click="storageLinkSubmit"
                                >
                                    Storage Link
                                </CommonButton>
                            </div>
                            <div class="col-span-12 sm:col-span-4">
                                <CommonButton
                                    v-tooltip.top="
                                        'Run all scheduled tasks immediately. This will execute any tasks that are scheduled to run in your application.'
                                    "
                                    class="w-full"
                                    variant="secondary"
                                    icon="heroicons-outline:cpu-chip"
                                    :processing="manuallyCronRun"
                                    @click="cronRunSubmit"
                                >
                                    Manually Cron Run
                                </CommonButton>
                            </div>
                        </div>
                    </form>
                </div>
            </SettingsLayout>
        </PanelLayout>
    </AppLayout>
</template>
