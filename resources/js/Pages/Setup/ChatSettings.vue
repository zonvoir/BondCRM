<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';

const props = defineProps({
    liveChatSettings: {
        type: Object,
        required: true,
    },
});

const types = ref([
    { name: 'Pusher', code: 'pusher' },
    { name: 'Ably', code: 'ably' },
]);

const selectedType =
    types.value.find(t => t.code === props.liveChatSettings?.data?.type) ||
    types.value[0];

const isPusher = ref(selectedType.code === 'pusher');

const form = useForm({
    type: selectedType,
    pusher_app_id: props.liveChatSettings?.data?.pusher_app_id ?? '',
    pusher_app_key: props.liveChatSettings?.data?.pusher_app_key ?? '',
    pusher_app_secret: props.liveChatSettings?.data?.pusher_app_secret ?? '',
    pusher_host: props.liveChatSettings?.data?.pusher_host ?? '',
    pusher_port: props.liveChatSettings?.data?.pusher_port ?? '',
    pusher_scheme: props.liveChatSettings?.data?.pusher_scheme ?? 'https',
    pusher_app_cluster: props.liveChatSettings?.data?.pusher_app_cluster ?? '',
    ably_key: props.liveChatSettings?.data?.ably_key ?? '',
});

const handleTypeChange = type => {
    isPusher.value = type.name === 'Pusher';
};

const submit = () => {
    form.post(route('settings.chat'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <AppLayout title="Settings">
        <PanelLayout>
            <CommonCard>
                <form method="post" @submit.prevent="submit">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-12 sm:col-auto">
                            <CommonSelect
                                v-model="form.type"
                                :options="types"
                                label="Type"
                                required
                                optionLabel="name"
                                placeholder="Select a type"
                                :error="form.errors.type"
                                @change="handleTypeChange"
                            />
                        </div>

                        <!-- Pusher Configuration -->
                        <template v-if="isPusher">
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Pusher App ID"
                                    v-model="form.pusher_app_id"
                                    :error="form.errors.pusher_app_id"
                                />
                            </div>
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Pusher App Key"
                                    v-model="form.pusher_app_key"
                                    :error="form.errors.pusher_app_key"
                                />
                            </div>
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Pusher App Secret"
                                    v-model="form.pusher_app_secret"
                                    :error="form.errors.pusher_app_secret"
                                />
                            </div>
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Pusher Host"
                                    v-model="form.pusher_host"
                                    :error="form.errors.pusher_host"
                                />
                            </div>
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Pusher Port"
                                    v-model="form.pusher_port"
                                    :error="form.errors.pusher_port"
                                />
                            </div>
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Pusher Scheme"
                                    v-model="form.pusher_scheme"
                                    :error="form.errors.pusher_scheme"
                                />
                            </div>
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Pusher Cluster"
                                    v-model="form.pusher_app_cluster"
                                    :error="form.errors.pusher_app_cluster"
                                />
                            </div>
                        </template>

                        <!-- Ably Configuration -->
                        <template v-else>
                            <div class="col-span-12 sm:col-auto">
                                <CommonInput
                                    label="Ably Key"
                                    v-model="form.ably_key"
                                    :error="form.errors.ably_key"
                                />
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-start py-5">
                        <CommonButton
                            :processing="form.processing"
                            variant="primary"
                            type="submit"
                        >
                            Save Changes
                        </CommonButton>
                    </div>
                </form>
            </CommonCard>
        </PanelLayout>
    </AppLayout>
</template>
