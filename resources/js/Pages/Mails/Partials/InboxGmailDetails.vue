<script setup>
defineProps({
    message: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="min-h-screen bg-white text-slate-800">
        <div class="flex items-start gap-3 border-b border-slate-200">
            <button
                class="grid size-8 place-items-center rounded hover:bg-slate-100"
            >
                <span class="i-heroicons-arrow-left-20-solid size-5"></span>
            </button>
            <h1 class="text-lg font-medium">{{ message?.subject }}</h1>
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
                        {{ message?.email?.created_at }}
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

                <!-- Toolbar -->
                <div
                    class="flex items-center gap-4 border-b border-slate-200 px-4 py-2 text-sm text-slate-600"
                >
                    <span>Normal</span>
                    <div class="h-4 w-px bg-slate-300"></div>
                    <button class="font-bold">B</button>
                    <button class="italic">I</button>
                    <button class="underline">U</button>
                    <button class="line-through">S</button>
                    <button class="underline decoration-dotted">U</button>
                </div>

                <!-- Editor -->
                <div class="min-h-[240px] px-4 py-3">
                    <textarea
                        class="h-56 w-full resize-none outline-none placeholder:text-slate-400"
                        placeholder="Type your message here..."
                    ></textarea>
                </div>

                <!-- Actions -->
                <div
                    class="flex items-center justify-between border-t border-slate-200 px-4 py-3"
                >
                    <button
                        class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        Send
                    </button>
                    <button
                        class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800"
                    >
                        <span
                            class="i-heroicons-paper-clip-20-solid size-5"
                        ></span>
                        Attach
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
