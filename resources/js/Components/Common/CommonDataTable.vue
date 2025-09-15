<script setup>
import { defineProps, ref, watch, defineEmits, handleError } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import { Link, router } from '@inertiajs/vue3';

const params = route().params;
const currentPerPage = ref(params?.perPage || 10);

const props = defineProps({
    routeName: {
        type: String,
        default: '',
    },
    routeParams: {
        type: Object,
        default: null,
    },
    data: {
        type: Object,
        required: true,
        default: () => [],
    },
    sortField: {
        type: String,
        default: '',
    },
    sortOrder: {
        type: Number,
        default: -1,
    },
    showSerialNumber: {
        type: Boolean,
        default: true,
    },
    serialNumberText: {
        type: String,
        default: 'S.N',
    },
    checkbox: {
        type: Boolean,
        default: false,
    },
    modelSelection: {
        type: [Array, Object, null],
        default: null,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const links = ref(props?.data?.meta?.links ?? props?.data?.links);
const internalSelection = ref(props.modelSelection);
const emit = defineEmits(['update:modelSelection', 'rowClick']);

watch(
    () => props.modelSelection,
    val => {
        internalSelection.value = val;
    }
);

watch(internalSelection, val => {
    emit('update:modelSelection', val);
});

const onRowClick = event => {
    emit('rowClick', event);
};

const updatedUrlParams = pageNo => {
    const mergedParams = {
        ...params,
        ...pageNo,
        ...props?.routeParams,
    };
    return route(props?.routeName, mergedParams);
};

const handlePageTotal = e => {
    const mergedParams = {
        ...params,
        perPage: e.target.value,
    };
    router.visit(route(props?.routeName, mergedParams));
};

const buildPagination = () => {
    const meta = props.data?.meta ?? props.data;
    const totalPages = meta?.last_page ?? meta?.lastPage ?? 1;
    const currentPage = meta?.current_page ?? meta?.currentPage ?? 1;

    const pages = [];

    if (totalPages <= 7) {
        for (let i = 1; i <= totalPages; i++) pages.push(i);
    } else {
        pages.push(1);
        if (currentPage > 3) pages.push('...');

        const start = Math.max(2, currentPage - 2);
        const end = Math.min(totalPages - 1, currentPage + 2);

        for (let i = start; i <= end; i++) pages.push(i);

        if (currentPage < totalPages - 2) pages.push('...');
        pages.push(totalPages);
    }

    return { pages, currentPage, totalPages };
};
</script>

<template>
    <div
        class="w-full rounded-md border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
    >
        <div class="overflow-x-auto rounded-t-md">
            <div class="card w-full">
                <DataTable
                    :value="data?.data"
                    :sortField="sortField"
                    :sortOrder="sortOrder"
                    v-model:selection="internalSelection"
                    dataKey="id"
                    tableStyle="min-width: 50rem"
                    @row-click="onRowClick"
                    :loading="loading"
                    :pt="{
                        column: {
                            headerCell: {
                                class: [
                                    '!bg-indigo-50 !text-indigo-700 text-base dark:!bg-indigo-900/30 dark:!text-indigo-200 cursor-pointer !py-2',
                                ],
                            },
                            bodyCell: {
                                class: 'cursor-pointer !whitespace-nowrap',
                            },
                        },
                    }"
                >
                    <Column
                        v-if="checkbox"
                        selectionMode="multiple"
                        headerStyle="width: 3rem"
                    ></Column>
                    <Column v-if="showSerialNumber" :header="serialNumberText">
                        <template #body="slotProps">
                            {{
                                ((data?.meta?.current_page ??
                                    data?.current_page) -
                                    1) *
                                    (data?.meta?.per_page ??
                                        data?.current_page) +
                                slotProps.index +
                                1
                            }}
                        </template>
                    </Column>
                    <slot />
                    <template #empty>
                        <div
                            class="flex flex-col items-center justify-center py-14 text-gray-500 dark:text-gray-400"
                        >
                            <CommonIcon
                                class="h-18 w-18"
                                icon="line-md:file-document"
                            />
                            <p class="text-lg font-medium">No data found</p>
                            <p class="text-sm">
                                There are no records to display at the moment.
                            </p>
                        </div>
                    </template>
                </DataTable>

                <div
                    class="mx-3 flex flex-col gap-6 py-4 lg:flex-row lg:items-center lg:justify-between"
                >
                    <!-- Items per page selector -->
                    <div class="flex items-center gap-4">
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Show
                        </label>
                        <select
                            @change="handlePageTotal"
                            v-model="currentPerPage"
                            class="rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm transition-all duration-200 hover:border-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 dark:hover:border-gray-500 dark:focus:border-blue-400"
                        >
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span
                            class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-400"
                        >
                            Showing {{ data?.meta?.from ?? data?.from }} –
                            {{ data?.meta?.to ?? data?.to }} of
                            {{ data?.meta?.total ?? data?.total }}
                        </span>
                    </div>

                    <!-- Pagination links -->
                    <nav
                        class="flex items-center gap-2"
                        aria-label="Pagination Navigation"
                    >
                        <Link
                            class="rounded-lg border px-3 py-2 text-sm hover:bg-gray-100 disabled:opacity-50 dark:hover:bg-gray-800"
                            :disabled="buildPagination().currentPage === 1"
                            :href="
                                updatedUrlParams({
                                    page: buildPagination().currentPage - 1,
                                })
                            "
                        >
                            Prev
                        </Link>

                        <template
                            v-for="(page, i) in buildPagination().pages"
                            :key="i"
                        >
                            <span
                                v-if="page === '...'"
                                class="px-3 py-2 text-sm text-gray-400"
                                >…</span
                            >

                            <Link
                                v-else
                                :href="updatedUrlParams({ page })"
                                class="flex h-10 min-w-10 items-center justify-center rounded-lg border px-3 text-sm font-medium transition-all duration-200"
                                :class="
                                    page === buildPagination().currentPage
                                        ? 'border-blue-500 bg-blue-50 text-blue-600 dark:border-blue-400 dark:bg-blue-900/20 dark:text-blue-400'
                                        : 'border-gray-200 text-gray-700 hover:border-gray-300 dark:border-gray-700 dark:text-gray-300 dark:hover:border-gray-600 dark:hover:bg-gray-800'
                                "
                            >
                                {{ page }}
                            </Link>
                        </template>

                        <Link
                            class="rounded-lg border px-3 py-2 text-sm hover:bg-gray-100 disabled:opacity-50 dark:hover:bg-gray-800"
                            :disabled="
                                buildPagination().currentPage ===
                                buildPagination().totalPages
                            "
                            :href="
                                updatedUrlParams({
                                    page: buildPagination().currentPage + 1,
                                })
                            "
                        >
                            Next
                        </Link>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
