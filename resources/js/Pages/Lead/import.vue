<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonFile from '@/Components/Common/CommonFile.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';

const props = defineProps({
    status: {
        type: Array,
        default: () => [],
    },
    source: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    id: null,
    name: '',
    source: '',
    status: '',
});

const tableHeaders = [
    { key: 'name', label: 'Name' },
    { key: 'position', label: 'Position' },
    { key: 'company', label: 'Company' },
    { key: 'description', label: 'Description' },
    { key: 'country', label: 'Country' },
    { key: 'zip', label: 'Zip' },
    { key: 'city', label: 'City' },
    { key: 'state', label: 'State' },
    { key: 'address', label: 'Address' },
    { key: 'status', label: 'Status' },
    { key: 'source', label: 'Source' },
    { key: 'email', label: 'Email' },
    { key: 'website', label: 'Website' },
    { key: 'phoneNumber', label: 'Phone Number' },
    { key: 'leadValue', label: 'Lead Value' },
];
</script>

<template>
    <AppLayout title="import Lead">
        <PanelLayout>
            <div class="flex items-center justify-between">
                <div class="text-xl leading-tight font-bold">Import Leads</div>
                <div>
                    <Link>
                        <div
                            class="flex items-center justify-center rounded-md bg-indigo-100 p-1 py-1 transition-colors group-hover:bg-indigo-200 dark:bg-gray-700 dark:text-white"
                        >
                            Download Sample
                        </div>
                    </Link>
                </div>
            </div>

            <div class="my-4">
                <CommonCard>
                    <div class="overflow-hidden">
                        <!-- Table Header Info -->
                        <div
                            class="border-b border-gray-200 bg-gray-50 px-6 py-4"
                        >
                            <h3 class="text-lg font-semibold text-gray-900">
                                Simulation Data
                                <span class="text-sm font-normal text-blue-600"
                                    >Max 100 rows are shown</span
                                >
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                If you are satisfied with the results upload the
                                file again and click import.
                            </p>
                        </div>

                        <!-- Table Container with Horizontal Scroll -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <!-- Table Head -->
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            v-for="header in tableHeaders"
                                            :key="header.key"
                                            class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase"
                                        >
                                            {{ header.label }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-200 bg-white"
                                >
                                    <tr
                                        class="transition-colors duration-150 hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            India
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800"
                                                >New</span
                                            >
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            <span
                                                class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800"
                                                >Facebook</span
                                            >
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-blue-600 hover:text-blue-800"
                                        >
                                            user@example.com
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-blue-600 hover:text-blue-800"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm font-medium whitespace-nowrap text-green-600"
                                        >
                                            Sample Data
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="my-5 grid grid-cols-12 gap-4">
                        <div class="col-span-3">
                            <CommonFile
                                label="Choose file"
                                required
                                :multiple="true"
                                accepted-formats=".xls,.xlsx,.csv.,.pdf"
                            />
                        </div>

                        <div class="col-span-3">
                            <CommonSelect
                                v-model="form.status"
                                label="Status"
                                :options="status"
                                optionLabel="name"
                                :error="form.errors.status"
                                required
                            />
                        </div>

                        <div class="col-span-3">
                            <CommonSelect
                                v-model="form.source"
                                label="Lead Source"
                                :options="source"
                                optionLabel="name"
                                :error="form.errors.source"
                                required
                            />
                        </div>
                    </div>
                </CommonCard>
            </div>
        </PanelLayout>
    </AppLayout>
</template>
