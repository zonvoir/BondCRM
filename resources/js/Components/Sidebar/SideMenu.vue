<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Dialog,
    DialogPanel,
    Menu,
    MenuButton,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import Navigation from '@/Components/Sidebar/Navigation.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import DropdownLink from '../DropdownLink.vue';
import CommonLink from '@/Components/Common/CommonLink.vue';
import { isDark, switchTheme } from '@/Composables/theme';
import { RoleEnum } from '@/enums/RoleEnum';
import { useRoles } from '@/composables/useRoles';
import SetupNavigation from '@/Components/Sidebar/SetupNavigation.vue';
import CommonAvatar from '../Common/CommonAvatar.vue';
import CommonInput from '../Common/CommonInput.vue';
import CommonPopover from '../Common/CommonPopover.vue';
import CommonBadge from '../Common/CommonBadge.vue';
import CommonButton from '../Common/CommonButton.vue';
import CommonTabs from '../Common/CommonTabs.vue';

const { hasRole } = useRoles();
const { props } = usePage();
const sidebarOpen = ref(false);

const toggleTheme = () => {
    switchTheme();
};

const shortName = (name, type = false) => {
    if (!name) {
        return 'N/A';
    }
    const nameArray = name.trim().split(' ');
    if (type === true) {
        return nameArray[0];
    }
    if (nameArray.length === 1) {
        return nameArray[0][0].toUpperCase();
    }
    return nameArray[0][0].toUpperCase() + nameArray[1][0].toUpperCase();
};

const notificationPopover = ref()
const handleToggleNotification = (event) => {

    notificationPopover.value.popover.toggle(event)
}


const tabs = [
  {
    id: 'overview',
    value: '0',
    title: 'Overview',
    icon: 'pi pi-home',         // any PrimeIcons class name
    content: 'Overview Content here…',
  },
  {
    id: 'analytics',
    value: '1',
    title: 'Analytics',
    icon: 'pi pi-chart-line',
    content: 'Analytics Content here…',
  },
  {
    id: 'settings',
    value: '2',
    title: 'Settings',
    icon: 'pi pi-cog',
    content: 'Settings Content here…',
    // link: '/settings',          
  },
]
</script>
<template>
    <TransitionRoot as="template" :show="sidebarOpen">
        <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">
            <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0"
                enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-900/80" />
            </TransitionChild>

            <div class="fixed inset-0 flex">
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                    enter-from="-translate-x-full" enter-to="translate-x-0"
                    leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                    leave-to="-translate-x-full">
                    <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                            enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100"
                            leave-to="opacity-0">
                            <div class="absolute top-0 left-full flex w-16 justify-center pt-5">
                                <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <CommonIcon class="h-6 w-6 text-white" icon="heroicons-outline:x-mark" />
                                </button>
                            </div>
                        </TransitionChild>
                        <!-- Sidebar component, swap this element with another sidebar if you like -->
                        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 dark:bg-gray-800">
                            <div class="flex h-16 shrink-0 items-center">
                                <template v-if="isDark">
                                    <img class="h-8 w-auto cursor-pointer" :src="props?.logos?.logo_white" alt="logo" />
                                </template>
                                <template v-else>
                                    <img class="h-8 w-auto cursor-pointer" :src="props?.logos?.logo_dark" alt="logo" />
                                </template>
                            </div>

                            <nav class="flex flex-1 flex-col">
                                <ul role="list" class="flex flex-1 flex-col gap-y-1">
                                    <template v-if="usePage().props?.setup?.isSetup">
                                        <SetupNavigation />
                                    </template>
                                    <template v-else>
                                        <Navigation />
                                    </template>

                                    <li class="mt-auto">
                                        <CommonLink :href="route('settings')" :active="route().current('settings')
                                            ">
                                            <CommonIcon
                                                class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600"
                                                icon="vscode-icons:file-type-config" />
                                            Settings
                                        </CommonLink>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex grow flex-col gap-y-5  border-r  bg-primary-900 dark:bg-primary-900  ">
            <div class="flex h-16 shrink-0 items-center justify-between bg-primary-950 p-5 ">
                <template v-if="isDark">
                    <div class="flex items-center justify-between">
                        <img class="h-7 w-auto cursor-pointer" :src="props?.logos?.logo_white" alt="logo" />
                        <span v-if="!usePage().props?.setup?.isSetup"
                            class="rounded-full relative  border-2 border-light bg-transparent text-light flex items-center h-5 w-5 justify-center">
                            <span
                                class="w-1 h-1 rounded-full bg-light absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span></span>
                    </div>
                </template>
                <template v-else>
                    <div class="flex items-center justify-between w-full">

                        <img class="h-7 w-auto cursor-pointer" :src="props?.logos?.logo_dark" alt="logo" />

                        <span v-if="!usePage().props?.setup?.isSetup"
                            class="rounded-full relative  border-2 border-light bg-transparent text-light flex items-center h-5 w-5 justify-center">
                            <span
                                class="w-1 h-1 rounded-full bg-light absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span></span>
                    </div>
                </template>

                <template v-if="usePage().props?.setup?.isSetup">
                    <Link :href="route('dashboard')">
                    <CommonIcon class="h-6 w-6 cursor-pointer text-light" icon="heroicons:x-circle" />
                    </Link>
                </template>
            </div>
            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-2">
                    <template v-if="usePage().props?.setup?.isSetup">
                        <div class="px-4 flex flex-col gap-3  overflow-y-auto">
                            <SetupNavigation />
                        </div>
                    </template>
                    <template v-else>
                        <div class="px-4 flex flex-col gap-3  overflow-y-auto">
                            <Navigation />
                        </div>
                    </template>
                    <div class="mt-auto flex flex-col gap-3 p-4">
                        <div class="p-3 rounded-xl bg-primary-950 flex items-center gap-3">
                            <CommonAvatar class="!w-10 !h-10 min-w-10 border-3 border-light"
                                image="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-4.0.3&crop=faces&fit=crop&w=200&h=200" />
                            <div class="flex items-center justify-between w-full">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-bold text-light">Alisa Roy</h3>
                                    <CommonIcon icon="heroicons:chevron-down" class="text-light" />
                                </div>
                                <div>
                                    <CommonIcon class="w-5 h-5 rotate-180 text-light cursor-pointer"
                                        icon="heroicons:arrow-left-start-on-rectangle" />
                                </div>
                            </div>
                        </div>


                    </div>
                </ul>
            </nav>
        </div>
    </div>

    <div class="lg:pl-64 ">
        <div
            class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-xs sm:gap-x-6 sm:px-6 lg:px-2 dark:border-gray-700 dark:bg-gray-900">
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                <span class="sr-only">Open sidebar</span>
                <CommonIcon class="h-6 w-6" icon="heroicons-outline:bars-3" />
            </button>
            <CommonInput
                InputClass="hidden lg:flex border-none !rounded-lg bg-background placeholder:!text-secondary-200 text-sm font-normal min-w-full   lg:min-w-xs "
                icon="solar:magnifer-linear" iconClass="hidden text-primary-900 !w-4 !h-4 " placeholder="Search ..." />
            <!-- Separator -->
            <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true" />



            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                <form class="relative flex flex-1" action="#" method="GET"></form>
                <div class="flex items-center gap-x-4 lg:gap-x-6">

                    <div class="flex  items-center gap-6">
                        <button @click="handleToggleNotification($event)" type="button"
                            class="-m-2.5 cursor-pointer p-3 bg-background hover:bg-secondary-100/40 transition-colors rounded-full text-primary-900 hover:text-primary-950">
                            <span class="sr-only">View notifications</span>
                            <CommonIcon class="h-4 w-4" icon="lucide:bell-ring" />
                        </button>
                        <CommonPopover ref="notificationPopover" unstyled>
                            <div class="min-w-sm rounded-md shadow-md overflow-hidden">
                                <div class="bg-primary-950 p-4 pb-5">
                                    <div class="flex items-center gap-2 pb-4 ">
                                        <h6 class="flex gap-2 text-light text-base">Your Notifications
                                            <CommonBadge value="140 New" />
                                        </h6>
                                        <div class="flex-grow border-t-1 border-light"></div>
                                        <CommonBadge value=" Clear all"
                                            class="cursor-pointer !bg-light !text-primary-950" />

                                    </div>
                                </div>
                                <div class="max-h-[50vh] h-[50vh] w-full bg-light border overflow-y-auto">
                                    This is notifications
                                    
                                </div>
                            </div>
                        </CommonPopover>

                        <button @click="toggleTheme" type="button"
                            class="-m-2.5 cursor-pointer p-2.5 text-gray-400 hover:text-gray-500">
                            <CommonIcon v-if="isDark" class="h-5 w-5" icon="solar:moon-outline" />
                            <CommonIcon v-else class="h-6 w-6" icon="heroicons-outline:sun" />
                        </button>
                    </div>


                    <!-- Profile dropdown -->
                    <Menu as="div" class="relative">
                        <MenuButton class="-m-1.5 flex items-center p-1.5">
                            <div
                                class="group flex items-center gap-6 hover:bg-gray-100 px-5 py-1 rounded-xl cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <CommonAvatar size="normal" class="!bg-background !w-10 min-w-10 shadow !h-10"
                                        image="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-4.0.3&crop=faces&fit=crop&w=200&h=200"
                                        shape="circle" />
                                    <div class="hidden lg:flex flex-col items-start ">
                                        <h5 class=" text-base leading-6 font-semibold text-secondary-800 dark:text-light"
                                            aria-hidden="true">
                                            {{
                                                shortName(
                                                    usePage().props.auth?.user?.name,
                                                    true
                                                )
                                            }}
                                        </h5>
                                        <p class="text-xs font-normal text-secondary-200">Employee</p>
                                    </div>
                                </div>
                                <span class="hidden lg:flex bg-primary-950 text-light rounded-sm">
                                    <CommonIcon icon="heroicons:chevron-down" />
                                </span>
                            </div>
                        </MenuButton>
                        <transition enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95">
                            <MenuItems
                                class="absolute right-0 z-10 mt-4 w-52 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-gray-900/5 focus:outline-hidden dark:bg-gray-900">
                                <DropdownLink
                                    class="hover:bg-primary-900 flex items-center gap-2 p-2  rounded-t-md hover:text-light"
                                    :href="route('profile.edit')">
                                    <span>
                                        <CommonIcon icon="heroicons:user" class="w-4 h-4" />
                                    </span>
                                    Profile
                                </DropdownLink>
                                <DropdownLink
                                    class="hover:bg-primary-900 flex items-center gap-2 p-2 cursor-pointer  rounded-b-md hover:text-light"
                                    :href="route('logout')" method="post" as="button">
                                    <CommonIcon icon="lucide:log-out" class="w-4 h-4" />
                                    Log Out
                                </DropdownLink>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>
        <slot />
    </div>
</template>
