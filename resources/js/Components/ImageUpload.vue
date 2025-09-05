<script setup>
import { onMounted, ref } from 'vue';
import CommonButton from './Common/CommonButton.vue';

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

const fileInput = ref(null);
const src = ref(null);

onMounted(() => {
    if (props.image) {
        src.value = props.image;
    }
});

const emit = defineEmits(['update:modelValue']);

const onFileSelect = event => {
    const file = event.target.files[0];

    if (!file) return;

    const reader = new FileReader();

    reader.onload = e => {
        src.value = e.target.result;
        emit('update:modelValue', e.target.result);
    };

    reader.readAsDataURL(file);
};

const removeImage = () => {
    src.value = null;
    emit('update:modelValue', null);
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

const UploadImage = () => {
    fileInput.value.click();
};
</script>

<template>
    <div class="flex w-full gap-3 rounded-md p-2 shadow">
        <div
            class="flex h-24 w-24 min-w-24 rounded-md border-2 border-dashed border-indigo-500 p-2"
        >
            <div class="flex items-center">
                <img
                    v-if="src"
                    :src="src"
                    alt="Image"
                    class="h-full w-full object-contain"
                />
                <input
                    type="file"
                    ref="fileInput"
                    @change="onFileSelect"
                    class="hidden"
                    :accept="acceptedFormats"
                />
            </div>
        </div>
        <div class="flex w-full flex-col justify-around">
            <div class="flex items-center gap-2">
                <CommonButton
                    @click="UploadImage"
                    variant="primary"
                    class="w-1/2 !px-2 !py-1"
                >
                    Upload
                </CommonButton>
                <CommonButton
                    icon="heroicons:trash"
                    @click="removeImage"
                    v-tooltip.top="'Remove Image'"
                    class="w-1/2"
                    variant="gray"
                >
                </CommonButton>
            </div>
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
        </div>

        <div class="mt-2" v-show="error">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ error }}
            </p>
        </div>
    </div>
</template>
