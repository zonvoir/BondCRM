<script setup>
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonEditor from '@/Components/Common/CommonEditor.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    message: {
        type: Object,
        required: true,
    },
});
const contentText = ref();
const onTextChange = e => {
    form.message = e.htmlValue;
};

const form = useForm({
    id: props.message?.email?.id ?? null,
    to: props.message?.email?.sender_email ?? null,
    subject: props.message?.subject ?? null,
    message: null,
});

const submit = () => {
    form.post(route('employee.gmail.reply'), {
        onSuccess: () => {
            contentText.value = '';
        },
    });
};
</script>

<template>
    <div class="min-h-screen  text-gray-900 dark:text-gray-100  transition-colors duration-300">
        <!-- Header -->
        <div
            class="flex items-center gap-3 rounded-md bg-white/50 dark:bg-gray-800/50 backdrop-blur-md border border-gray-200 dark:border-gray-700 px-6 py-4 shadow">
            <button
                class="grid place-items-center rounded-md bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <span class="i-heroicons-arrow-left-20-solid size-6 text-gray-700 dark:text-gray-200"></span>
            </button>
            <h1 class="text-md font-semibold tracking-wide">{{ message?.subject }}</h1>
            <span class="ml-2 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white shadow">
                {{ route().params.type }}
            </span>
        </div>

        <!-- Body -->
        <div class="mt-6 space-y-6">
            <!-- Sender Info -->
            <div
                class="flex items-center gap-3 rounded-md bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 px-6 py-4 shadow transition-colors duration-300">
                <div
                    class="flex size-10 items-center justify-center rounded-full bg-indigo-600 text-white font-bold shadow">
                    {{ message?.email?.sender_name[0] }}
                </div>
                <div class="flex flex-col text-sm">
                    <span class="font-medium flex items-center gap-2">
                        {{ message?.email.sender_name }}
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                    </span>
                    <span class="text-gray-500 dark:text-gray-400 text-xs">
                        {{ message?.email?.created_at }} • to me
                    </span>
                </div>
            </div>

            <!-- Email Body -->
            <div class="rounded-md bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 px-6 py-6 shadow leading-relaxed transition-colors duration-300"
                v-html="message?.email?.body">
            </div>

            <!-- Reply Section -->
            <div
                class="rounded-md bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 shadow overflow-hidden transition-colors duration-300">
                <!-- To -->
                <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-sm">
                    <span class="w-10 text-gray-500 dark:text-gray-400">To:</span>
                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-gray-100 dark:bg-gray-700 px-3 py-1 text-xs text-gray-900 dark:text-gray-100">
                        <span>{{ message?.email?.sender_email }}</span>
                        <button class="hover:text-pink-500 transition">×</button>
                    </div>
                </div>

                <!-- Subject -->
                <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-700 text-sm font-medium">
                    {{ message?.subject }}
                </div>

                <!-- Toolbar -->
                <div
                    class="flex items-center gap-4 px-6 py-2 border-b border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-300">
                    <span class="text-gray-500 dark:text-gray-400">Normal</span>
                    <div class="h-4 w-px bg-gray-300 dark:bg-gray-600"></div>
                    <button class="hover:text-gray-800 font-bold">B</button>
                    <button class="hover:text-gray-800 italic">I</button>
                    <button class="hover:text-gray-800 underline">U</button>
                    <button class="hover:text-gray-800 line-through">S</button>
                    <button class="hover:text-gray-800 underline decoration-dotted">U</button>
                </div>

                <!-- Editor -->
                <CommonEditor v-model="contentText" @textChange="onTextChange"
                    editorStyle="height: 240px; background: transparent; color: inherit;" />

                <!-- Actions -->
                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <CommonButton @click="submit" :processing="form.processing">
                        Send
                        <CommonIcon class="transform rotate-45"  icon="heroicons-outline:paper-airplane"/>
                    </CommonButton>
                </div>
            </div>
        </div>
    </div>
</template>
