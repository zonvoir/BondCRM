<script setup>
import { useForm } from '@inertiajs/vue3';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonTextarea from '@/Components/Common/CommonTextarea.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';

const props = defineProps({
    openAiSettings: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    assistantName: props?.openAiSettings?.assistant_name ?? null,
    assistantId: props?.openAiSettings?.assistant_id ?? null,
    apiKey: props?.openAiSettings?.api_key ?? null,
    prompt: props?.openAiSettings?.prompt ?? null,
});

const submit = () => {
    form.post(route('setup.openai.save'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <AppLayout title="OpenAi Settings">
        <PanelLayout>
            <CommonCard>
                <form method="post" class="grid grid-cols-12 gap-3">
                    <div class="col-span-12 sm:col-span-6">
                        <CommonInput
                            label="Assistant Name"
                            v-model="form.assistantName"
                            :error="form.errors.assistantName"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-6">
                        <CommonInput
                            label="Assistant ID"
                            v-model="form.assistantId"
                            :error="form.errors.assistantId"
                        />
                    </div>
                    <div class="col-span-12 sm:col-span-12">
                        <CommonInput
                            label="API Key"
                            v-model="form.apiKey"
                            :error="form.errors.apiKey"
                        />
                    </div>

                    <div class="col-span-12 sm:col-span-12">
                        <CommonTextarea
                            v-model="form.prompt"
                            :error="form.errors.apiKey"
                        />
                    </div>

                    <div class="col-span-12 sm:col-span-4">
                        <CommonButton @click="submit">Submit</CommonButton>
                    </div>
                </form>
            </CommonCard>
        </PanelLayout>
    </AppLayout>
</template>
