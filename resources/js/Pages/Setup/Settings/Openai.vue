<script setup>
import SettingsLayout from '@/Layouts/SettingsLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm } from '@inertiajs/vue3';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonTextarea from '@/Components/Common/CommonTextarea.vue';
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

const openAiSettings = props.data.openAiSettings;

const form = useForm({
    apiKey: openAiSettings?.api_key ?? null,
    prompt: openAiSettings?.prompt ?? null,
});

const submit = () => {
    form.post(route('setup.openai.save'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <AppLayout :title="data?.title">
        <PanelLayout>
            <SettingsLayout :menu="menuSettings">
                <form method="post" class="grid grid-cols-12 gap-3">
                    <div class="col-span-12 sm:col-span-12">
                        <CommonInput
                            label="API Key"
                            v-model="form.apiKey"
                            :error="form.errors.apiKey"
                        />
                    </div>

                    <div class="col-span-12 sm:col-span-12">
                        <CommonTextarea
                            placeholder="Rules"
                            v-model="form.prompt"
                            :error="form.errors.prompt"
                        />
                    </div>

                    <div class="col-span-12 sm:col-span-4">
                        <CommonButton @click="submit">Submit</CommonButton>
                    </div>
                </form>
            </SettingsLayout>
        </PanelLayout>
    </AppLayout>
</template>
