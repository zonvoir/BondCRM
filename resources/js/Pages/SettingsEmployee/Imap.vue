<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonRadioButton from '@/Components/Common/CommonRadioButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    settings: {
        type: Object,
        required: false,
    },
    smtpType: {
        type: String,
        required: true,
    },
});

const encryption = [
    { label: 'TLS', value: 'tls' },
    { label: 'SSL', value: 'ssl' },
];

const initialEncryption =
    encryption?.find(item => item.label === props.settings?.imap_encryption)
        ?.value ?? '';

const form = useForm({
    imapServer: props.settings?.imap_server ?? '',
    imapUserName: props.settings?.imap_user_name ?? '',
    password: props.settings?.password ?? '',
    imapPort: props.settings?.imap_port ?? '',
    folder: props.settings?.folder ?? '',
    imapEncryption: initialEncryption,
    type: props.smtpType,
});

const submit = () => {
    form.post(route('employee.imap.settings.save'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <AppLayout title="Settings">
        <PanelLayout>
            <CommonCard>
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
            </CommonCard>
        </PanelLayout>
    </AppLayout>
</template>
