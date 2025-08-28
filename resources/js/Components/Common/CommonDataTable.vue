<template>
    <div class="card w-full">
        <DataTable
            :value="data?.data"
            :sortField="sortField"
            :sortOrder="sortOrder"
            v-model:selection="internalSelection"
            dataKey="id"
            tableStyle="min-width: 50rem"
            @row-click="onRowClick"
            :pt="{
                column: {
                    headerCell: { class: 'cursor-pointer' },
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
                        ((data?.meta?.current_page ?? data?.current_page) - 1) *
                            (data?.meta?.per_page ?? data?.current_page) +
                        slotProps.index +
                        1
                    }}
                </template>
            </Column>
            <slot />
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
                        'border-indigo-500 text-indigo-500': link?.active,
                    }"
                    :href="link?.url + otherArgument"
                    v-html="link?.label"
                />
            </template>
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref, watch, defineEmits } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

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
