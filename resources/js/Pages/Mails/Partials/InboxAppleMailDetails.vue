<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonEditor from '@/Components/Common/CommonEditor.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
    message: {
        type: Object,
        required: true,
    },
});

const contentText = ref();
const onTextChange = e => {
    form.replyData = e.htmlValue;
};

const form = useForm({
    message_id: props.message?.email?.id,
    replyData: null,
    attachments: null,
});

const submit = () => {
    form.post(
        route('employee.webmail.reply', {
            type: 'applemail',
            folder: route().params.type,
        }),
        {
            onSuccess: () => {
                contentText.value = '';
            },
        }
    );
};
</script>

<template>
    <div
        class="min-h-screen text-gray-900 transition-colors duration-300 dark:text-gray-100"
    >
        <div
            class="flex items-center gap-3 rounded-md border border-gray-200 bg-white/50 px-6 py-4 shadow backdrop-blur-md dark:border-gray-700 dark:bg-gray-800/50"
        >
            <button
                class="grid place-items-center rounded-md bg-gray-100 transition hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600"
            >
                <span
                    class="i-heroicons-arrow-left-20-solid size-6 text-gray-700 dark:text-gray-200"
                ></span>
            </button>
            <h1 class="text-md font-semibold tracking-wide">
                {{ message?.subject_title }}
            </h1>
            <span
                class="ml-2 rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white shadow"
                >{{ route().params.type }}</span
            >
        </div>

        <div class="mt-6 space-y-6">
            <div
                class="flex items-center gap-3 rounded-md border border-gray-200 bg-white/50 px-6 py-4 shadow transition-colors duration-300 dark:border-gray-700 dark:bg-gray-800/50"
            >
                <div
                    class="flex size-10 items-center justify-center rounded-full bg-indigo-600 font-bold text-white shadow"
                >
                    {{ message?.email?.sender_name[0] }}
                </div>
                <div class="flex flex-col text-sm">
                    <span class="flex items-center gap-2 font-medium">
                        {{ message?.email.sender_name }}
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <!-- {{ message?.created_at }} -->
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ message?.created_at }} • to me
                    </span>
                </div>
            </div>

            <p
                v-if="message?.email?.body"
                class="rounded-md border border-gray-200 bg-white/50 px-6 py-6 leading-relaxed shadow transition-colors duration-300 dark:border-gray-700 dark:bg-gray-800/50"
                v-html="message.email.body"
            ></p>

            <p
                v-else
                class="rounded-md border border-gray-200 bg-white/50 px-6 py-6 text-sm leading-relaxed shadow transition-colors duration-300 dark:border-gray-700 dark:bg-gray-800/50"
            >
                No Content Found
            </p>

            <!-- <div class="my-6 h-px bg-slate-200"></div> -->

            <div
                class="overflow-hidden rounded-md border border-gray-200 bg-white/50 shadow transition-colors duration-300 dark:border-gray-700 dark:bg-gray-800/50"
            >
                <!-- To -->
                <div
                    class="flex items-center gap-2 border-b border-gray-200 px-6 py-4 text-sm dark:border-gray-700"
                >
                    <span class="w-10 text-gray-500 dark:text-gray-400"
                        >To:</span
                    >

                    <div
                        class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-900 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <span class="text-slate-700">{{
                            message?.email?.sender_email
                        }}</span>
                        <button class="transition hover:text-indigo-500">
                            ×
                        </button>
                    </div>
                    <!-- <div class="ml-auto flex items-center gap-4 text-slate-500"></div> -->
                </div>

                <!-- Subject -->
                <div
                    class="border-b border-gray-200 px-6 py-3 text-sm font-medium dark:border-gray-700"
                >
                    {{ message?.subject }}
                </div>

                <CommonEditor
                    v-model="contentText"
                    @textChange="onTextChange"
                    editorStyle="height: 240px; background: transparent; color: inherit;"
                />

                <!-- Actions -->
                <div
                    class="flex items-center justify-between border-t border-gray-200 px-6 py-4 dark:border-gray-700"
                >
                    <CommonButton @click="submit" :processing="form.processing">
                        Send
                        <CommonIcon
                            class="rotate-45 transform"
                            icon="heroicons-outline:paper-airplane"
                        />
                    </CommonButton>
                </div>
            </div>
        </div>
    </div>
</template>
