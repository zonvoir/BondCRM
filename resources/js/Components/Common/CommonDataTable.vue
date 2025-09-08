<script setup>
import { defineProps, ref, watch, defineEmits } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import CommonIcon from '@/Components/Common/CommonIcon.vue';

const props = defineProps({
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
    otherArgument: {
        type: String,
        default: '',
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
                            bodyCell: { class: 'cursor-pointer' },
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

                <div class="flex flex-wrap items-center justify-center py-5">
                    <template v-for="(link, key) in links">
                        <div
                            v-if="link?.url === null"
                            :key="key"
                            class="mr-1 mb-1 rounded-sm border px-4 py-3 text-sm leading-4 text-gray-400 dark:border-gray-600 dark:text-gray-500"
                            v-html="link?.label"
                        />
                        <Link
                            v-else
                            :key="'link-' + key"
                            class="mr-1 mb-1 rounded-sm border px-4 py-3 text-sm leading-4 hover:bg-gray-100 focus:ring-3 focus:ring-indigo-300 focus:outline-hidden dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                            :class="{
                                'bg-white dark:border-indigo-500 dark:bg-gray-800 dark:text-indigo-400':
                                    link?.active,
                                'border-indigo-500 text-indigo-500':
                                    link?.active,
                            }"
                            :href="link?.url + otherArgument"
                            v-html="link?.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
