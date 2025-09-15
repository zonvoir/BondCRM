<script setup>
import { computed, ref } from 'vue';
import CommonInput from './CommonInput.vue';
import CommonIcon from './CommonIcon.vue';
import CommonButton from './CommonButton.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    label: {
        type: String,
        default: 'Default Label',
    },
    labelClass: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    inputClass: {
        type: String,
        default: '',
    },
    buttonClass: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    routeName: {
        type: String,
        default: '',
        required: true,
    },
    inputName: {
        type: String,
        default: '',
        required: true,
    },
});

const isEditing = ref(false);

const form = useForm({
    [props.inputName]: null,
});

const isFilled = computed(() => {
    const v = form[props.inputName];
    return v != null && String(v).trim().length > 0;
});

const handleSubmit = () => {
    form.post(route(props.routeName), {
        onSuccess: () => {
            isEditing.value = false;
            form.reset();
        },
    });
};
</script>

<template>
    <div class="flex w-full flex-col items-start gap-1">
        <label :class="labelClass" class="flex text-sm"
            >{{ label }}
            <p class="mx-1 text-red-600" v-if="required">*</p>
        </label>
        <template v-if="isEditing">
            <div class="flex w-full flex-col gap-1">
                <div class="start flex justify-between">
                    <div class="flex w-full flex-col">
                        <CommonInput
                            v-model="form[props.inputName]"
                            type="text"
                            :placeholder="`Enter ${placeholder}...`"
                            :class="[
                                '!mr-2 flex !w-full flex-col rounded border px-2 py-1',
                                inputClass,
                            ]"
                            :error="form.errors[props.inputName]"
                        />
                    </div>

                    <!-- when filled, show Save; else show Close -->
                    <CommonButton
                        v-if="isFilled"
                        @click="handleSubmit"
                        variant="gray"
                        :class="['ml-2 h-max rounded p-2 !py-3', buttonClass]"
                    >
                        <CommonIcon icon="heroicons:check" />
                    </CommonButton>

                    <CommonButton
                        v-else
                        @click="isEditing = false"
                        variant="danger"
                        :class="['ml-2 h-max rounded p-2 !py-3', buttonClass]"
                    >
                        <CommonIcon icon="heroicons:x-mark" />
                    </CommonButton>
                </div>
            </div>
        </template>

        <template v-else>
            <div class="flex w-full items-start gap-2">
                <slot />
                <CommonButton
                    @click="isEditing = true"
                    variant="primary"
                    class="rounded p-2 !py-3"
                >
                    <CommonIcon icon="heroicons:plus" />
                </CommonButton>
            </div>
        </template>
    </div>
</template>
