<template>
    <div class="card">
        <DataTable
            :value="data?.data"
            :sortField="sortField"
            :sortOrder="sortOrder"
            tableStyle="min-width: 50rem"
        >
            <Column v-if="showSerialNumber" :header="serialNumberText">
                <template #body="slotProps">
                    {{
                        (data?.meta?.current_page - 1) * data?.meta?.per_page +
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
                    :key="`link-${key}`"
                    class="mr-1 mb-1 rounded-sm border px-4 py-3 text-sm leading-4 hover:bg-gray-100 focus:ring-3 focus:ring-indigo-300 focus:outline-hidden dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                    :class="{
                        'bg-white dark:border-indigo-500 dark:bg-gray-800 dark:text-indigo-400':
                            link?.active,
                        'border-indigo-500 text-indigo-500': link?.active,
                    }"
                    :href="link?.url"
                    v-html="link?.label"
                />
            </template>
        </div>
    </div>
</template>

<script setup>
import { defineProps, ref } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

const props = defineProps({
    data: {
        type: Array,
        required: true,
        default: () => [],
    },
    sortField: {
        type: String,
        default: '',
        required: false,
    },
    sortOrder: {
        type: Number,
        default: -1,
        required: false,
    },
    showSerialNumber: {
        type: Boolean,
        default: true,
    },
    serialNumberText: {
        type: String,
        default: 'S.N',
    },
});

const links = ref(props?.data?.meta?.links);
</script>
