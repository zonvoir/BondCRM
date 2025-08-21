<script setup>
import { useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonFile from '@/Components/Common/CommonFile.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonColor from '@/Components/Common/CommonColor.vue';

const props = defineProps({
    generalSettings: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    appName: props?.generalSettings?.data?.app_name,
    description: props?.generalSettings?.data?.app_description,
    appLogo: '',
    themeColor: props?.generalSettings?.data?.theme_color,
});

const submit = () => {
    form.post(route('settings.general.pwa'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <CommonCard>
        <form method="post">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-12 sm:col-auto">
                    <CommonInput
                        label="App Name"
                        required
                        v-model="form.appName"
                        :error="form.errors.appName"
                    />
                </div>
                <div class="col-span-12 sm:col-auto">
                    <CommonInput
                        label="Description"
                        required
                        v-model="form.description"
                        :error="form.errors.description"
                    />
                </div>
            </div>
            <div class="grid grid-cols-1 gap-3 xl:grid-cols-2">
                <div class="grid grid-cols-1 gap-3 py-3 xl:grid-cols-3">
                    <div>
                        <CommonFile
                            label="Logo (512 x 512)"
                            acceptedFormats="image/png"
                            required
                            v-model="form.appLogo"
                            :error="form.errors.appLogo"
                            :image="generalSettings?.data?.app_logo"
                        />
                    </div>
                </div>

                <div class="my-3 grid grid-cols-12">
                    <div class="col-span-12 sm:col-6">
                        <CommonColor
                            required
                            v-model="form.themeColor"
                            :error="form.errors.themeColor"
                            label="Theme Color"
                        />
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-4 gap-4"></div>

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
    </CommonCard>
</template>
