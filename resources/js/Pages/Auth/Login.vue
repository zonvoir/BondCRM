<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    socialiteSettings: {
        type: Object,
    },
});

const page = usePage();

const form = useForm({
    email: 'admin@gmail.com',
    password: '123',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <div class="py-3" v-show="page?.props?.flash?.message">
            <p class="text-sm text-red-600 dark:text-red-400">
                {{ page.props.flash.message }}
            </p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>
            <div class="mt-4 flex w-full justify-between">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                        >Remember me</span
                    >
                </label>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Forgot your password?
                </Link>
            </div>
            <div class="mx-auto mt-4 w-100 text-center">
                <PrimaryButton
                    class="mt-6"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>

            <div class="max-w-5x mt-5 flex w-full space-x-2 overflow-hidden">
                <div
                    v-if="socialiteSettings?.google?.data?.status"
                    class="w-100 md:w-1/2"
                >
                    <a
                        :href="route('auth.login.socialite', 'google')"
                        class="rounded-circle tex-sm block inline-flex w-100 items-center justify-center border border-blue-700 px-4 py-3 text-center font-medium text-blue-700"
                    >
                        <CommonIcon icon="logos:google-icon" class="mx-2" />
                        Google
                    </a>
                </div>

                <!-- Right Side Login Form -->
                <div
                    v-if="socialiteSettings?.linkedin?.data?.status"
                    class="w-100 md:w-1/2"
                >
                    <a
                        :href="route('auth.login.socialite', 'linkedin-openid')"
                        class="rounded-circle block inline-flex w-100 items-center justify-center border border-blue-700 px-4 py-3 text-center text-sm font-medium text-blue-700"
                    >
                        <CommonIcon icon="logos:linkedin-icon" class="mx-2" />
                        Linkedin
                    </a>
                </div>
            </div>

            <div class="mx-auto mt-4 w-100 text-center">
                <p
                    class="ms-2 mb-4 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                >
                    Have no account yet?
                </p>
                <Link
                    :href="route('register')"
                    class="rounded-circle block w-100 border border-blue-700 px-4 py-3 text-sm font-medium text-blue-700"
                >
                    Registration
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
