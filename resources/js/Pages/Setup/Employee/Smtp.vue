<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import { Link, useForm } from '@inertiajs/vue3';
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonRadioButton from '@/Components/Common/CommonRadioButton.vue';

const props = defineProps({
    menuSettings: {
        type: Array,
    },
    data: {
        type: Array,
    },
});

const smtpSettings = props?.data?.smtpSettings;

const encryption = [
    { label: 'TLS', value: 'tls' },
    { label: 'SSL', value: 'ssl' },
];

const initialEncryption =
    encryption?.find(item => item.label === smtpSettings.mail_encryption)
        ?.value ?? '';

const form = useForm({
    mailDriver: smtpSettings.mail_driver ?? '',
    mailHost: smtpSettings.mail_host ?? '',
    mailPort: smtpSettings.mail_port ?? '',
    mail: smtpSettings.mail ?? '',
    password: smtpSettings.password ?? '',
    mailEncryption: initialEncryption,
    mailFromAddress: smtpSettings.mail_from_address ?? '',
    mailFromName: smtpSettings.mail_from_name ?? '',
});

const formEmailTest = useForm({
    email: '',
});

const submit = () => {
    form.post(route('employee.smtp.save'), {
        onSuccess: () => {},
    });
};

const emailTestSubmit = () => {
    formEmailTest.post(route('settings.email.test'), {
        onSuccess: () => {
            formEmailTest.reset();
        },
    });
};
</script>

<template>
    <AppLayout :title="data?.title">
        <PanelLayout>
            <SettingsLayout :menu="menuSettings">
                <form
                    method="post"
                    class="mb-5 flex flex-row items-center gap-6"
                >
                    <div>
                        <CommonInput
                            label="email"
                            placeholder="Enter Email"
                            required
                            type="email"
                            :error="formEmailTest?.errors?.email"
                            v-model="formEmailTest.email"
                        />
                    </div>
                    <div>
                        <CommonButton @click="emailTestSubmit" class="mt-5"
                            >Test</CommonButton
                        >
                    </div>
                </form>

                <form method="post">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="Mail Driver"
                                placeholder="Enter Mail Driver"
                                required
                                v-model="form.mailDriver"
                                :error="form.errors.mailDriver"
                            />
                        </div>
                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="Mail Host"
                                placeholder="Enter Mail Host"
                                required
                                v-model="form.mailHost"
                                :error="form.errors.mailHost"
                            />
                        </div>
                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="Mail Port"
                                placeholder="Enter Mail Port"
                                required
                                v-model="form.mailPort"
                                :error="form.errors.mailPort"
                            />
                        </div>
                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="Username/Mail"
                                placeholder="Enter Mail"
                                required
                                v-model="form.mail"
                                :error="form.errors.mail"
                            />
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="Password"
                                placeholder="Enter Password"
                                required
                                v-model="form.password"
                                :error="form.errors.password"
                                type="password"
                            />
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <CommonRadioButton
                                label="Mail Encryption"
                                name="encryption"
                                required
                                :options="encryption"
                                v-model="form.mailEncryption"
                                :error="form.errors.mailEncryption"
                            />
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="Mail From Address"
                                placeholder="Enter From Address"
                                required
                                v-model="form.mailFromAddress"
                                :error="form.errors.mailFromAddress"
                            />
                        </div>
                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="Mail From Name"
                                placeholder="Enter From Name"
                                required
                                v-model="form.mailFromName"
                                :error="form.errors.mailFromName"
                            />
                        </div>
                        <div class="col-span-12 mt-6 sm:col-span-4">
                            <CommonButton @click="submit">Submit</CommonButton>
                        </div>
                    </div>
                </form>
            </SettingsLayout>
        </PanelLayout>
    </AppLayout>
</template>
