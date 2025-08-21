<template>
    <div class="flex items-center">
        <ToggleSwitch
            :modelValue="modelValue"
            @update:modelValue="updateValue"
            class="relative"
        >
            <template #handle="{ checked }">
                <CommonIcon
                    :icon="checked ? 'bi:check-circle-fill' : 'bi:x-circle'"
                />
            </template>
        </ToggleSwitch>
    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';
import ToggleSwitch from 'primevue/toggleswitch';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    modelValue: Boolean,
});

const emit = defineEmits(['update:modelValue']);

const updateValue = value => {
    emit('update:modelValue', value);
};
</script>
