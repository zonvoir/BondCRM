<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonFile from '@/Components/Common/CommonFile.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import { ref } from 'vue';

const props = defineProps({
    status: {
        type: Array,
        default: () => [],
    },
    source: {
        type: Array,
        default: () => [],
    },
    sampleFile: {
        type: String,
    },
});

const simulateData = ref();
const file = ref();

const form = useForm({
    file: '',
    source: '',
    status: '',
});

const tableHeaders = [
    { key: 'name', label: 'Name' },
    { key: 'company', label: 'Company' },
    { key: 'email', label: 'Email' },
    { key: 'phoneNumber', label: 'Phone Number' },
    { key: 'status', label: 'Status' },
    { key: 'source', label: 'Source' },
    { key: 'address', label: 'Address' },
    { key: 'zip', label: 'Zip' },
    { key: 'city', label: 'City' },
    { key: 'state', label: 'State' },
    { key: 'country', label: 'Country' },
    { key: 'website', label: 'Website' },
    { key: 'position', label: 'Position' },
    { key: 'leadValue', label: 'Lead Value' },
    { key: 'description', label: 'Description' },
];

const simulateImportFile = e => {
    file.value = e;
    form.file = e;
};
const simulateImport = () => {
    axios
        .post(
            route('employee.lead.import.simulate'),
            {
                file: file.value,
                source: form.source,
                status: form.status,
            },
            {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            }
        )
        .then(response => {
            simulateData.value = response?.data?.data;
        })
        .catch(error => {});
};

const handleSubmit = () => {
    form.post(route('employee.lead.import.save'), {
        onSuccess: () => {},
    });
};
</script>

<template>
    <AppLayout title="import Lead">
        <PanelLayout>
            <div class="flex items-center justify-between">
                <div class="text-xl leading-tight font-bold">Import Leads</div>
                <div>
                    <a :href="sampleFile" download>
                        <div
                            class="flex items-center justify-center rounded-md bg-indigo-100 p-1 py-1 transition-colors group-hover:bg-indigo-200 dark:bg-gray-700 dark:text-white"
                        >
                            Download Sample
                        </div>
                    </a>
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
                                        v-for="(data, index) in simulateData"
                                        :key="index"
                                        class="transition-colors duration-150 hover:bg-gray-50"
                                    >
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[0] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[1] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[3] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[4] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800"
                                                >{{ data[5] }}</span
                                            >
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            <span
                                                class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800"
                                                >{{ data[6] }}</span
                                            >
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[6] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[7] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[8] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[9] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[10] }}
                                        </td>

                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[11] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[12] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[13] }}
                                        </td>
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            {{ data[14] }}
                                        </td>
                                    </tr>

                                    <tr
                                        v-if="!simulateData"
                                        class="transition-colors duration-150 hover:bg-gray-50"
                                    >
                                        <!-- Name -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>

                                        <!-- Company -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Sample Data
                                        </td>

                                        <!-- Email -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-blue-600 hover:text-blue-800"
                                        >
                                            user@example.com
                                        </td>

                                        <!-- Phone Number -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            +91-9876543210
                                        </td>

                                        <!-- Status -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800"
                                                >New</span
                                            >
                                        </td>

                                        <!-- Source -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            <span
                                                class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800"
                                                >Facebook</span
                                            >
                                        </td>

                                        <!-- Address -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            123 Tech Street
                                        </td>

                                        <!-- Zip -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            226001
                                        </td>

                                        <!-- City -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Lucknow
                                        </td>

                                        <!-- State -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            UP
                                        </td>

                                        <!-- Country -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            India
                                        </td>

                                        <!-- Website -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-blue-600 hover:text-blue-800"
                                        >
                                            https://fastcodelab.com
                                        </td>

                                        <!-- Position -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            Software Engineer
                                        </td>

                                        <!-- Lead Value -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-gray-900"
                                        >
                                            5000
                                        </td>

                                        <!-- Description -->
                                        <td
                                            class="px-4 py-3 text-sm font-medium whitespace-nowrap text-green-600"
                                        >
                                            Interested in Laravel solutions
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="my-5 grid grid-cols-12 gap-4">
                        <div class="col-span-3">
                            <CommonFile
                                @update:modelValue="simulateImportFile"
                                label="Choose file"
                                required
                                accepted-formats=".xls,.xlsx,.csv"
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

                        <div class="col-span-12 my-4">
                            <CommonButton
                                @click="handleSubmit"
                                :processing="form.processing"
                                >Import</CommonButton
                            >
                            <CommonButton variant="gray" @click="simulateImport"
                                >Simulate Import</CommonButton
                            >
                        </div>
                    </div>
                </CommonCard>
            </div>
        </PanelLayout>
    </AppLayout>
</template>
