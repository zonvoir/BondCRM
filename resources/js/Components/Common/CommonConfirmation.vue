<template>
    <TransitionRoot as="template" :show="modelValue">
        <Dialog class="relative z-10" @close="handleClose">
            <!-- Backdrop -->
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500/75 transition-opacity dark:bg-gray-900/80"
                />
            </TransitionChild>

            <!-- Modal panel -->
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg dark:bg-gray-800"
                        >
                            <!-- Content -->
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <!-- Icon Slot -->
                                    <div
                                        class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                                    >
                                        <slot name="icon">
                                            <ExclamationTriangleIcon
                                                class="h-6 w-6 text-red-600"
                                                aria-hidden="true"
                                            />
                                        </slot>
                                    </div>
                                    <!-- Title & Message -->
                                    <div
                                        class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                                    >
                                        <DialogTitle
                                            as="h3"
                                            class="text-base font-semibold text-gray-900"
                                        >
                                            {{ title }}
                                        </DialogTitle>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                {{ message }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Actions -->
                            <div
                                class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"
                            >
                                <CommonButton
                                    variant="danger"
                                    class="ml-2"
                                    @click="confirm"
                                >
                                    {{ confirmText }}
                                </CommonButton>

                                <CommonButton
                                    @click="cancel"
                                    ref="cancelButtonRef"
                                >
                                    {{ cancelText }}
                                </CommonButton>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';
import CommonButton from '@/Components/Common/CommonButton.vue';

/**
 * Props:
 * - modelValue: controls the dialog's visibility (v-model)
 * - title: the confirmation title
 * - message: the confirmation message
 * - confirmText: text for the confirm button
 * - cancelText: text for the cancel button
 */
const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: 'Confirm Action',
    },
    message: {
        type: String,
        default: 'Are you sure you want to proceed?',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
});

// Emits:
// - update:modelValue: for v-model binding
// - confirm: when the confirm button is clicked
// - cancel: when the cancel action occurs
const emits = defineEmits(['update:modelValue', 'confirm', 'cancel']);

const cancelButtonRef = ref(null);

const handleClose = () => {
    emits('update:modelValue', false);
    emits('cancel');
};

const confirm = () => {
    emits('confirm');
    emits('update:modelValue', false);
};

const cancel = () => {
    emits('cancel');
    emits('update:modelValue', false);
};
</script>
