<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonEditor from '@/Components/Common/CommonEditor.vue';

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
    <div class="min-h-screen bg-white text-slate-800">
        <div class="flex items-start gap-3 border-b border-slate-200">
            <button
                class="grid size-8 place-items-center rounded hover:bg-slate-100"
            >
                <span class="i-heroicons-arrow-left-20-solid size-5"></span>
            </button>
            <h1 class="text-lg font-medium">{{ message?.subject_title }}</h1>
            <span
                class="ml-2 rounded bg-slate-100 px-2 py-0.5 text-xs font-medium"
                >{{ route().params.type }}</span
            >
        </div>

        <div class="px-8 py-6">
            <div class="flex items-center gap-3">
                <div
                    class="flex size-8 items-center justify-center rounded bg-slate-200 font-bold"
                >
                    {{ message?.email?.sender_name[0] }}
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="inline-flex items-center gap-1">
                        {{ message?.email.sender_name }}
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        {{ message?.created_at }}
                    </span>
                    <span>•</span>
                    <span>to me</span>
                </div>
            </div>

            <p class="mt-6" v-html="message?.email?.body"></p>

            <div class="my-6 h-px bg-slate-200"></div>

            <div class="overflow-hidden">
                <!-- To -->
                <div class="border-b border-slate-200 px-4 py-3 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-8 text-slate-500">To:</span>
                        <div
                            class="inline-flex items-center gap-2 rounded bg-slate-100 px-2 py-0.5"
                        >
                            <span class="text-slate-700">{{
                                message?.email?.sender_email
                            }}</span>
                            <button class="text-slate-500 hover:text-slate-700">
                                ×
                            </button>
                        </div>
                        <div
                            class="ml-auto flex items-center gap-4 text-slate-500"
                        ></div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="border-b border-slate-200 px-4 py-3 text-sm">
                    <span class="text-slate-700">{{ message?.subject }}</span>
                </div>

                <CommonEditor
                    v-model="contentText"
                    @textChange="onTextChange"
                    editorStyle="height: 240px"
                />

                <!-- Actions -->
                <div
                    class="flex items-center justify-between border-t border-slate-200 px-4 py-3"
                >
                    <CommonButton @click="submit" :processing="form.processing">
                        Send
                    </CommonButton>
                </div>
            </div>
        </div>
    </div>
</template>
