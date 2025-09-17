<script setup>
import { ref, nextTick, watch } from 'vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonPopover from '@/Components/Common/CommonPopover.vue';

const props = defineProps({
    label: { type: String, default: '' },
    required: { type: Boolean, default: false },
    labelClass: { type: String, default: '' },
    suggestionOptions: {
        type: Array,
        default: () => [],
    },
    modelValue: { type: Array, default: () => [] },
});

const emit = defineEmits(['update:modelValue']);

const allOptions = ref([...props.suggestionOptions]);
const availableOptions = ref([...props.suggestionOptions]);

const tags = ref([]);
const newTag = ref('');
const inputRef = ref(null);
const suggestions = ref(null);

watch(
    () => props.modelValue,
    val => {
        const arr = Array.isArray(val) ? [...val] : [];
        tags.value = arr;
        const set = new Set(arr);
        availableOptions.value = allOptions.value.filter(o => !set.has(o));
    },
    { immediate: true }
);

const commit = () => emit('update:modelValue', [...tags.value]);

const addTag = text => {
    const val = (text ?? newTag?.value)?.trim();
    if (val && !tags.value.includes(val)) {
        tags?.value.push(val);
        availableOptions.value = availableOptions.value.filter(o => o !== val);
        commit();
    }
    newTag.value = '';
    nextTick(() => inputRef.value?.focus());
};

const removeTag = i => {
    const removed = tags.value.splice(i, 1);
    if (
        removed &&
        !availableOptions.value.includes(removed) &&
        allOptions.value.includes(removed)
    ) {
        availableOptions.value.push(removed);
    }
    commit();
};

const openSuggestions = event => {
    if (availableOptions.value?.length === 0) return;
    suggestions.value?.popover?.show(event);
};

const closeSuggestions = () => {
    suggestions.value?.popover?.hide();
};
</script>

<template>
    <div>
        <label
            v-if="props.label"
            :class="[
                'block text-sm font-medium text-gray-700 dark:text-gray-300',
                labelClass,
            ]"
        >
            {{ props.label }}
            <p v-if="required" class="mx-1 text-red-600">*</p>
        </label>

        <div
            class="flex flex-wrap items-center gap-2 rounded-md border border-gray-300 px-2 py-1"
        >
            <!-- chips -->
            <span
                v-for="(tag, i) in tags"
                :key="tag + i"
                class="flex items-center gap-1 rounded-full bg-gray-200 px-2 py-1 text-sm"
            >
                {{ tag }}
                <button
                    @click="removeTag(i)"
                    class="hover:text-gray-800 focus:outline-none"
                >
                    <CommonIcon
                        icon="heroicons:x-circle"
                        class="h-5 w-5 cursor-pointer"
                    />
                </button>
            </span>

            <!-- input -->
            <input
                ref="inputRef"
                v-model="newTag"
                @focus="openSuggestions($event)"
                @click="openSuggestions($event)"
                @input="closeSuggestions"
                @keyup.enter.prevent="addTag()"
                @keydown.tab.prevent="addTag()"
                @keydown.space.prevent="addTag()"
                placeholder="Tags"
                class="border-none outline-none focus:ring-0"
            />

            <!-- popover -->
            <CommonPopover unstyled ref="suggestions">
                <div
                    class="dark:bg-dark w-56 rounded-md border bg-white p-2 shadow-md"
                >
                    <div
                        v-if="availableOptions.length"
                        class="flex flex-col gap-2"
                    >
                        <button
                            v-for="option in availableOptions"
                            :key="option"
                            class="rounded px-2 py-1 text-left hover:bg-gray-100 dark:hover:bg-gray-700"
                            @click="addTag(option)"
                        >
                            {{ option }}
                        </button>
                    </div>
                    <div v-else class="text-sm text-gray-400">No options</div>
                </div>
            </CommonPopover>
        </div>
    </div>
</template>
