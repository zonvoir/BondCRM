<script setup>
import { ref, nextTick } from 'vue';
import CommonIcon from './CommonIcon.vue';

const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    required: {
        type: Boolean,
        default: false,
    },
    labelClass: {
        type: String,
        default: '',
    },
});

const tags = ref([]);
const newTag = ref('');
const inputRef = ref(null);

const addTag = () => {
    const text = newTag.value.trim();
    if (text) {
        tags.value.push(text);
        newTag.value = '';
        nextTick(() => inputRef.value?.focus());
    }
};

const removeTag = i => tags.value.splice(i, 1);
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
            <p class="mx-1 text-red-600" v-if="required">*</p>
        </label>

        <div
            class="flex flex-wrap items-center gap-2 rounded-md border border-gray-300 px-2 py-2"
        >
            <span
                v-for="(tag, i) in tags"
                :key="tag + i"
                class="text-dark flex items-center gap-1 rounded-full bg-gray-200 px-2 py-1 text-sm"
            >
                {{ tag }}
                <button
                    @click="removeTag(i)"
                    class="text-dark hover:text-gray-800 focus:outline-none"
                >
                    <CommonIcon
                        icon="heroicons:x-circle"
                        class="h-5 w-5 cursor-pointer"
                    />
                </button>
            </span>

            <input
                ref="inputRef"
                v-model="newTag"
                @keyup.enter.prevent="addTag"
                @keydown.tab.prevent="addTag"
                @keydown.space.prevent="addTag"
                placeholder="Tags"
                class="border-none outline-none focus:shadow-none focus:ring-0 focus:outline-none"
            />
        </div>
    </div>
</template>
