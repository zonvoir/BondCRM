<script setup>
import { onMounted, ref } from 'vue';
import FileUpload from 'primevue/fileupload';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    label: {
        type: String,
        default: '',
        required: false,
    },
    labelClass: {
        type: String,
        default: '',
        required: false,
    },
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
    fileClass: {
        type: String,
        default: 'w-full',
    },
    acceptedFormats: {
        type: String,
        default: 'image/*',
    },
    image: {
        type: String,
        required: false,
    },
});

const model = defineModel({
    type: String,
    required: true,
});

const src = ref(null);

onMounted(() => {
    if (props.image) {
        src.value = props.image;
    }
});

const emit = defineEmits(['update:modelValue']);

const onFileSelect = event => {
    const file = event.files[0];
    const reader = new FileReader();

    reader.onload = e => {
        src.value = e.target.result;
        emit('update:modelValue', file);
    };

    reader.readAsDataURL(file);
};

const removeImage = () => {
    src.value = null;
    emit('update:modelValue', null);
};
</script>

<template>
    <div class="flex w-full gap-3 border-none p-2 shadow dark:bg-gray-800">
        <div
            class="relative flex h-24 w-24 min-w-24 rounded-md border-2 border-dashed border-indigo-500 p-2"
        >
            <div
                v-if="src"
                class="absolute top-1 left-1 flex h-4 w-4 items-end"
            >
                <CommonIcon
                    @click="removeImage"
                    v-tooltip.top="'Remove Image'"
                    icon="heroicons-outline:trash"
                    class="top-2 h-4 w-4 cursor-pointer text-red-500"
                />
            </div>
            <div class="flex w-full items-center justify-center">
                <img
                    v-if="src"
                    :src="src"
                    alt="Image"
                    class="aspect-square h-full rounded-xl object-contain"
                />

                <div
                    v-else
                    class="flex items-center justify-center text-center text-xs text-gray-400"
                >
                    <CommonIcon class="h-6 w-6" icon="line-md:file-document" />
                    No File
                </div>
            </div>
        </div>

        <div class="flex w-full flex-col items-start justify-around">
            <FileUpload
                :pt="{
                    root: {
                        class: 'inline-flex!',
                    },
                }"
                mode="basic"
                @select="onFileSelect"
                :disabled="disabled"
                :accept="acceptedFormats"
                class="p-button-outlined right-1"
            >
                <template #chooseicon>
                    <CommonIcon
                        icon="heroicons-outline:arrow-up-tray"
                        class="h-4 w-4 cursor-pointer text-indigo-500"
                    />
                </template>
            </FileUpload>

            <div class="flex flex-col">
                <label
                    :class="[
                        labelClass,
                        'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300',
                    ]"
                >
                    <span class="flex items-center" v-if="label">
                        {{ label }}
                        <p class="mx-1 text-red-600" v-if="required">*</p>
                    </span>
                </label>
                <div v-show="error">
                    <p class="text-sm text-red-600 dark:text-red-400">
                        {{ error }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
