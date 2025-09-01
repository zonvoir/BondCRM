<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonFile from '@/Components/Common/CommonFile.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import { useCustomToast } from '@/Composables/useToast';
const { showToast } = useCustomToast();

const props = defineProps({
    timezones: {
        type: Array,
        required: true,
    },
    generalSettings: {
        type: Object,
        required: true,
    },
});

const clearCache = ref(false);
const storageLink = ref(false);
const manuallyCronRun = ref(false);
const isLink = ref(props.generalSettings?.data?.storage_link);

const form = useForm({
    footerText: props.generalSettings?.data?.footer_text ?? '',
    iconLogoDark: '',
    iconLogoWhite: '',
    logoDark: '',
    logoWhite: '',
    favicon: '',
    countries: props.generalSettings?.data?.countries ?? '',
    timezones: props.generalSettings?.data?.timezones ?? '',
});

const countries = ref([
    { name: 'Australia', code: 'AU' },
    { name: 'Brazil', code: 'BR' },
    { name: 'China', code: 'CN' },
    { name: 'Egypt', code: 'EG' },
    { name: 'France', code: 'FR' },
    { name: 'Germany', code: 'DE' },
    { name: 'India', code: 'IN' },
    { name: 'Japan', code: 'JP' },
    { name: 'Spain', code: 'ES' },
    { name: 'United States', code: 'US' },
]);

const submit = () => {
    form.post(route('settings.general.save'), {
        onSuccess: () => {},
    });
};

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
    <AppLayout title="Settings">
        <PanelLayout>
            <SettingsLayout>
                <form method="post">
                    <div class="grid grid-cols-3 gap-4 py-5">
                        <div class="col-span-12 sm:col-auto">
                            <CommonInput
                                label="Footer Text"
                                required
                                v-model="form.footerText"
                                :error="form.errors.footerText"
                            />
                        </div>
                        <div class="col-span-12 sm:col-auto">
                            <CommonSelect
                                v-model="form.countries"
                                :options="countries"
                                label="Currency"
                                required
                                optionLabel="name"
                                placeholder="Select a Country"
                                :error="form.errors.countries"
                            />
                        </div>
                        <div class="col-span-12 sm:col-auto">
                            <CommonSelect
                                v-model="form.timezones"
                                :options="timezones"
                                label="Timezones"
                                required
                                optionLabel="name"
                                placeholder="Select a Country"
                                :error="form.errors.timezones"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                        <div>
                            <CommonFile
                                label="Icon Logo Dark (512 x 512)"
                                acceptedFormats="image/png"
                                required
                                v-model="form.iconLogoDark"
                                :error="form.errors.iconLogoDark"
                                :image="generalSettings?.data?.icon_logo_dark"
                            />
                        </div>
                        <div>
                            <CommonFile
                                label="Icon Logo White (512 x 512)"
                                acceptedFormats="image/png"
                                required
                                v-model="form.iconLogoWhite"
                                :error="form.errors.iconLogoWhite"
                                :image="generalSettings?.data?.icon_logo_white"
                            />
                        </div>
                        <div>
                            <CommonFile
                                label="Logo Dark (300 x 55)"
                                acceptedFormats="image/png"
                                required
                                v-model="form.logoDark"
                                :error="form.errors.logoDark"
                                :image="generalSettings?.data?.logo_dark"
                            />
                        </div>
                        <div>
                            <CommonFile
                                label="Logo White (300 x 55)"
                                acceptedFormats="image/png"
                                required
                                v-model="form.logoWhite"
                                :error="form.errors.logoWhite"
                                :image="generalSettings?.data?.logo_white"
                            />
                        </div>
                        <div>
                            <CommonFile
                                label="Favicon (40 x 40)"
                                acceptedFormats="image/png"
                                required
                                v-model="form.favicon"
                                :error="form.errors.favicon"
                                :image="generalSettings?.data?.favicon"
                            />
                        </div>
                    </div>

                    <div class="flex justify-start py-5">
                        <CommonButton
                            :processing="form.processing"
                            variant="primary"
                            @click="submit"
                        >
                            Save Changes
                        </CommonButton>
                    </div>
                </form>

                <CommonCard class="mt-5">
                    <form method="post">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-12 sm:col-span-4">
                                <CommonButton
                                    v-tooltip.top="
                                        'Clear the application cache and optimize the system.'
                                    "
                                    class="w-full"
                                    variant="primary"
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
                                    variant="primary"
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
                                    variant="primary"
                                    icon="heroicons-outline:cpu-chip"
                                    :processing="manuallyCronRun"
                                    @click="cronRunSubmit"
                                >
                                    Manually Cron Run
                                </CommonButton>
                            </div>
                        </div>
                    </form>
                </CommonCard>
            </SettingsLayout>
        </PanelLayout>
    </AppLayout>
</template>
