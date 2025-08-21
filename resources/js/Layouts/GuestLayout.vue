<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const { props } = usePage();
const images = '/assets/img/login-img1.png';
import { isDark } from '@/Composables/theme';
</script>

<template>
    <div class="flex min-h-screen items-center justify-center">
        <div class="flex w-full max-w-5xl overflow-hidden rounded-lg shadow-lg">
            <div class="bg-login hidden p-10 md:block md:w-1/2">
                <div
                    class="relative mx-auto w-full max-w-3xl overflow-hidden rounded-lg"
                >
                    <img
                        :src="images"
                        alt="Slider Image"
                        class="h-full w-full object-contain transition-all duration-500"
                    />
                </div>
                <Link href="/" class="left-4 text-white"> Back to Home </Link>
            </div>

            <div class="w-full bg-white p-6 sm:p-10 md:w-1/2 dark:bg-gray-800">
                <div class="mx-auto mb-6 w-fit text-center">
                    <Link href="/">
                        <template v-if="isDark">
                            <img
                                class="h-10"
                                :src="props?.logos?.logo_white"
                                alt="logo"
                            />
                        </template>
                        <template v-else>
                            <img
                                class="h-10"
                                :src="props?.logos?.logo_dark"
                                alt="logo"
                            />
                        </template>
                    </Link>
                </div>

                <div>
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
.bg-login {
    background-image: url('/assets/img/bg-login.png');
}
</style>
