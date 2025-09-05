<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import { Link, useForm } from '@inertiajs/vue3';
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonRadioButton from '@/Components/Common/CommonRadioButton.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';

const props = defineProps({
    menuSettings: {
        type: Array,
    },
    data: {
        type: Array,
    },
});

const settings = props.data?.settings;

const encryption = [
    { label: 'TLS', value: 'tls' },
    { label: 'SSL', value: 'ssl' },
];

const initialEncryption =
    encryption?.find(item => item.label === settings?.imap_encryption)?.value ??
    '';

const form = useForm({
    imapServer: settings?.imap_server ?? '',
    imapUserName: settings?.imap_user_name ?? '',
    password: settings?.password ?? '',
    imapPort: settings?.imap_port ?? '',
    folder: settings?.folder ?? '',
    imapEncryption: initialEncryption,
    type: props.data.type,
});

const submit = () => {
    form.post(route('employee.imap.settings.save'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <AppLayout :title="data?.title">
        <PanelLayout>
            <SettingsLayout :menu="menuSettings">
                <form method="post">
                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="IMAP Server Address"
                                placeholder="example.com"
                                required
                                v-model="form.imapServer"
                                :error="form.errors.imapServer"
                            />
                        </div>
                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="IMAP User Name"
                                placeholder="imap.example.com"
                                required
                                v-model="form.imapUserName"
                                :error="form.errors.imapUserName"
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
                            <CommonInput
                                label="IMAP Port"
                                placeholder="Enter IMAP Port"
                                required
                                v-model="form.imapPort"
                                :error="form.errors.imapPort"
                            />
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <CommonInput
                                label="IMAP Folder"
                                placeholder=""
                                required
                                v-model="form.folder"
                                :error="form.errors.folder"
                            />
                        </div>

                        <div class="col-span-12 sm:col-span-4">
                            <CommonRadioButton
                                label="IMAP Encryption"
                                name="encryption"
                                required
                                :options="encryption"
                                v-model="form.imapEncryption"
                                :error="form.errors.imapEncryption"
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
