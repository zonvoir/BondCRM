<template>
    <div class="flex max-w-full flex-col gap-2 rounded-md border p-1">
        <!-- Dynamic Label -->
        <label class="text-base font-medium text-gray-800 dark:text-gray-300">
            {{ label || 'Attachments' }}
            <span v-if="required" class="ml-1 text-red-500">*</span>
        </label>

        <div class="flex items-center gap-4">
            <!-- left side -->
            <div
                class="relative flex flex-col items-center gap-2 rounded-md border p-2"
            >
                <CommonIcon
                    icon="heroicons:x-mark"
                    class="absolute -top-1 -left-1 h-5 w-5 cursor-pointer rounded-full bg-red-100 p-0.5 text-red-500 transition-colors hover:bg-red-100"
                    @click="removeAllFiles"
                    v-if="hasFiles"
                    title="Remove all files"
                />
                <CommonIcon
                    class="h-7 w-7"
                    :class="hasFiles ? 'text-blue-600' : 'text-gray-400'"
                    :icon="getFileIcon()"
                />
                <!-- Multiple files indicator -->
                <div
                    v-if="multiple && selectedFiles.length > 1"
                    class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-blue-500 text-xs text-white"
                >
                    {{ selectedFiles.length }}
                </div>

                <span class="text-[9px]">{{ fileDisplayText }}</span>
            </div>

            <!-- right side -->
            <div class="flex flex-col gap-2">
                <div>
                    <input
                        ref="fileInputRef"
                        type="file"
                        :class="[
                            'text-dark max-w-fit rounded-md border border-indigo-200 bg-gray-200 p-2 text-sm transition-shadow hover:shadow',
                            disabled
                                ? 'cursor-not-allowed opacity-50'
                                : 'cursor-pointer',
                        ]"
                        :multiple="multiple"
                        :accept="acceptedFormats"
                        :disabled="disabled"
                        @change="onFileSelect"
                    />
                </div>
                <div>
                    <!-- Show file info or accepted formats -->
                    <p
                        class="text-xs font-semibold"
                        :class="hasFiles ? 'text-blue-600' : 'text-gray-500'"
                    ></p>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="mt-1 text-xs font-medium text-red-500">
            {{ error }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    label: { type: String, default: '' },
    required: { type: Boolean, default: false },
    multiple: { type: Boolean, default: false },
    error: { type: String, default: '' },
    acceptedFormats: {
        type: String,
        default: '.pdf,.doc,.docx,.xls,.xlsx,.csv',
    },
    disabled: { type: Boolean, default: false },
    modelValue: { type: [File, Array], default: null },
});

const emit = defineEmits(['update:modelValue']);

const fileInputRef = ref(null);
const selectedFiles = ref([]);

// Initialize selected files
if (props.modelValue) {
    if (Array.isArray(props.modelValue)) {
        selectedFiles.value = [...props.modelValue]; // Create copy
    } else if (props.modelValue instanceof File) {
        selectedFiles.value = [props.modelValue];
    }
}

watch(
    () => props.modelValue,
    newValue => {
        if (newValue) {
            selectedFiles.value = Array.isArray(newValue)
                ? [...newValue]
                : [newValue];
        } else {
            selectedFiles.value = [];
        }
    }
);

// Computed properties
const hasFiles = computed(() => selectedFiles.value.length > 0);

const formatAcceptedTypes = computed(() => {
    if (!props.acceptedFormats) return 'All files';
    return props.acceptedFormats
        .split(',')
        .map(type => type.trim().replace('.', '').toUpperCase())
        .join(', ');
});

const fileDisplayText = computed(() => {
    if (props?.multiple && selectedFiles.value?.length > 1) {
        const totalSize = selectedFiles.value.reduce(
            (sum, file) => sum + file?.size,
            0
        );
        return `${selectedFiles.value.length} files (${formatFileSize(totalSize)})`;
    } else {
        const file = selectedFiles.value[0];
        if (file === undefined) return '';
        return formatFileSize(file?.size);
    }
});

// Methods
const onFileSelect = event => {
    const files = Array.from(event.target.files);
    if (files.length > 0) {
        selectedFiles.value = [...files]; // Create copy

        if (props.multiple) {
            emit('update:modelValue', [...files]); // Emit copy
        } else {
            emit('update:modelValue', files[0]);
        }
    }
};

// Remove ALL files (X button on icon)
const removeAllFiles = () => {
    selectedFiles.value = [];
    emit('update:modelValue', props.multiple ? [] : null);

    // Clear file input
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }
};

const getFileIcon = (file = null) => {
    const targetFile = file || selectedFiles.value[0];
    if (!targetFile) return 'heroicons:document';

    const extension = targetFile.name.split('.').pop()?.toLowerCase();
    const iconMap = {
        pdf: 'heroicons:document-text',
        xls: 'heroicons:table-cells',
        xlsx: 'heroicons:table-cells',
        csv: 'heroicons:table-cells',
        doc: 'heroicons:document-text',
        docx: 'heroicons:document-text',
        txt: 'heroicons:document',
        zip: 'heroicons:archive-box',
        rar: 'heroicons:archive-box',
    };

    return iconMap[extension] || 'heroicons:document';
};

const formatFileSize = bytes => {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
};
</script>
