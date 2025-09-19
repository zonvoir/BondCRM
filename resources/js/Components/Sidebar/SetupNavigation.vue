<script setup>
import { computed, onMounted, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonLinkContent from './Partials/CommonLinkContent.vue';
import CommonLink from '@/Components/Common/CommonLink.vue';

const navigation = ref(usePage()?.props?.setup?.menu);
const activeSubmenu = ref(null);

const toggleSubmenu = itemName => {
    if (activeSubmenu.value === itemName) {
        activeSubmenu.value = null;
    } else {
        activeSubmenu.value = itemName;
    }
};

const activeSubMenuName = computed(() => {
    const activeItem = navigation?.value?.find(item =>
        item?.subMenu?.some(subItem => subItem?.active)
    );

    return activeItem?.name ?? '';
});

onMounted(() => {
    toggleSubmenu(activeSubMenuName.value);
});
function dotLinesClass(index, lastIndex) {
    const base =
        'absolute left-1/2 top-1/2 transform -translate-x-1/2 border-1 border-dashed border-light/10'

    const translate = index === 0 && index !== lastIndex ? '-translate-y-1/3' : '-translate-y-2/3'
    const height = index === lastIndex ? 'h-[200%]' : 'h-[260%]'

    return `${base} ${translate} ${height}`
}
</script>
<template>
    <li v-for="menu in navigation" :key="menu?.name">
        <template v-if="menu?.subMenu.length === 0 && menu?.permission">
            <Link :href="menu?.href.length === 0 ? '#' : menu?.href" :class="[
                menu?.active
                    ? 'bg-primary-950 text-light dark:bg-gray-800'
                    : 'text-light hover:text-indigo-600',
                'group flex gap-x-3 rounded-lg py-2 px-3 text-sm leading-6 font-semibold hover:bg-primary-950 dark:hover:bg-gray-800',
            ]">
            <CommonLinkContent :menu="menu" :active-submenu="activeSubmenu" />
            </Link>
        </template>
        <template v-else>
            <a @click="toggleSubmenu(menu?.name)" :class="[
                menu?.active
                    ? 'bg-primary-950  text-light dark:bg-gray-800'
                    : 'text-light hover:text-indigo-600',
                'group flex cursor-pointer gap-x-3 px-3 rounded-lg py-2 text-sm leading-6 font-semibold hover:bg-primary-950 dark:hover:bg-gray-800',
            ]">
                <CommonLinkContent :menu="menu" :active-submenu="activeSubmenu" />
            </a>
        </template>

        <transition enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 transform -translate-y-2" enter-to-class="opacity-100 transform translate-y-0"
            leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 transform translate-y-0"
            leave-to-class="opacity-0 transform -translate-y-2">
            <ul v-if="activeSubmenu === menu?.name && menu?.subMenu.length > 0"
                class="mt-2 space-y-1 overflow-hidden  pl-2 p-4 bg-primary-950 rounded-lg">
                <li v-for="(subItem, index) in menu.subMenu" :key="index"
                    :class="['relative before:absolute before:-left-2 delay-300  before:h-full before:w-1 before:bg-white before:rounded-r-xl', subItem.active ? 'before:block' : 'before:hidden']">
                    <template v-if="subItem?.permission">
                        <div class="flex items-center gap-1 w-full">
                            <div class="relative h-full p-3">
                                <span :class="dotLinesClass(index, menu?.subMenu?.length - 1)"></span>
                                <CommonIcon :icon="subItem?.active
                                    ? 'bi:record-fill'
                                    : 'bi:record-fill'
                                    " :class="[
                                        subItem?.active
                                            ? 'text-light'
                                            : 'text-light group-hover:text-indigo-600',
                                        'h-4 w-4 shrink-0',
                                    ]" />
                            </div>
                            <CommonLink class="flex items-center gap-1 w-full"
                                customClass="hover:bg-primary-950 !items-start !py-2 !px-3 !w-full !text-sm !font-medium"
                                inActiveClass="bg-primary-950 text-light items-center hover:!bg-light hover:text-primary-950 "
                                activeClass="bg-light text-primary-950  items-center hover:!bg-light hover:text-primary-950"
                                :href="subItem?.href" :active="subItem?.active">

                                <CommonIcon :class="[
                                    subItem?.active
                                        ? 'text-primary-950'
                                        : 'text-light group-hover:text-primary-950',
                                    'h-5 w-5 font-semibold shrink-0',
                                ]" :icon="subItem.icon" />
                                {{ subItem?.name }}
                            </CommonLink>
                        </div>
                    </template>
                </li>
            </ul>
        </transition>
    </li>
</template>
