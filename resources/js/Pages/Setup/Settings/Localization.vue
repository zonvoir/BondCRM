<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm } from '@inertiajs/vue3';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';

const props = defineProps({
    menuSettings: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
    },
});

const localization = props.data.generalSettings;

const form = useForm({
    dateFormat: localization?.data?.date_format ?? null,
    timezones: localization?.data?.timezones ?? '',
    timeFormat: localization?.data?.time_format ?? '',
});

const submit = () => {
    form.post(route('localization.save'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <AppLayout :title="data?.title">
        <PanelLayout>
            <SettingsLayout :menu="menuSettings">
                <div class="col-span-12 my-3">
                    <CommonSelect
                        v-model="form.dateFormat"
                        :options="data?.dateFormats"
                        label="Date Format "
                        required
                        optionLabel="name"
                        placeholder="Select a Country"
                        :error="form.errors.dateFormat"
                    />
                </div>
                <div class="col-span-12 my-3">
                    <CommonSelect
                        v-model="form.timeFormat"
                        :options="data?.timeFormat"
                        label="Time Format"
                        required
                        optionLabel="name"
                        placeholder="Select a Country"
                        :error="form.errors.timeFormat"
                    />
                </div>
                <div class="col-span-12 my-3">
                    <CommonSelect
                        v-model="form.timezones"
                        :options="data?.timezones"
                        label="Timezones"
                        required
                        optionLabel="name"
                        placeholder="Select a Country"
                        :error="form.errors.timezones"
                    />
                </div>

                <div class="flex justify-start py-2">
                    <CommonButton
                        :processing="form.processing"
                        variant="primary"
                        @click="submit"
                    >
                        Save Changes
                    </CommonButton>
                </div>
            </SettingsLayout>
        </PanelLayout>
    </AppLayout>
</template>
