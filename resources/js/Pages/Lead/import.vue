<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import { useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonFile from '@/Components/Common/CommonFile.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import { ref } from 'vue';
import CommonSelectAdd from '@/Components/Common/CommonSelectAdd.vue';

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
        .catch(error => { });
};

const handleSubmit = () => {
    form.post(route('employee.lead.import.save'), {
        onSuccess: () => { },
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
                            class="flex items-center text-sm justify-center rounded-md bg-indigo-100 p-2 py-2 transition-colors group-hover:bg-indigo-200 dark:bg-gray-700 dark:text-white">
                            Download Sample
                        </div>
                    </a>
                </div>
            </div>

            <div class="my-4">
                <CommonCard customClass="bg-white dark:bg-gray-900" >
                    <div class="overflow-hidden">
                        <!-- Table Header Info -->
                        <div class="border-b border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-800  px-6 py-4 rounded-t-md">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Simulation Data
                                <span class="text-sm font-normal text-blue-600">Max 100 rows are shown</span>
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                If you are satisfied with the results upload the
                                file again and click import.
                            </p>
                        </div>

                        <!-- Table Container with Horizontal Scroll -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <!-- Table Head -->
                                <thead class="bg-gray-50 dark:bg-gray-800 ">
                                    <tr>
                                        <th v-for="header in tableHeaders" :key="header.key"
                                            class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap text-gray-500 uppercase">
                                            {{ header.label }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600 bg-white dark:bg-gray-800">
                                    <tr v-for="(data, index) in simulateData" :key="index"
                                        class="transition-colors duration-150 hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[0] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[1] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[3] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[4] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">{{
                                                    data[5] }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            <span
                                                class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">{{
                                                    data[6] }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[6] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[7] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[8] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[9] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[10] }}
                                        </td>

                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[11] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[12] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[13] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            {{ data[14] }}
                                        </td>
                                    </tr>

                                    <tr v-if="!simulateData" class="transition-colors duration-150 hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                        <!-- Name -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            Sample Data
                                        </td>

                                        <!-- Company -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            Sample Data
                                        </td>

                                        <!-- Email -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-blue-600 hover:text-blue-800">
                                            user@example.com
                                        </td>

                                        <!-- Phone Number -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            +91-9876543210
                                        </td>

                                        <!-- Status -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            <span
                                                class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">New</span>
                                        </td>

                                        <!-- Source -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            <span
                                                class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">Facebook</span>
                                        </td>

                                        <!-- Address -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            123 Tech Street
                                        </td>

                                        <!-- Zip -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            226001
                                        </td>

                                        <!-- City -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            Lucknow
                                        </td>

                                        <!-- State -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            UP
                                        </td>

                                        <!-- Country -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            India
                                        </td>

                                        <!-- Website -->
                                        <td
                                            class="px-4 py-3 text-sm whitespace-nowrap text-blue-600 hover:text-blue-800">
                                            https://fastcodelab.com
                                        </td>

                                        <!-- Position -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            Software Engineer
                                        </td>

                                        <!-- Lead Value -->
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-900 dark:text-white">
                                            5000
                                        </td>

                                        <!-- Description -->
                                        <td class="px-4 py-3 text-sm font-medium whitespace-nowrap text-green-600">
                                            Interested in Laravel solutions
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="my-5 dark:my-0 dark:pt-10 grid grid-cols-1 rounded-b-md gap-4 md:grid-cols-2 xl:grid-cols-3 dark:bg-gray-800 dark:p-3 ">
                        <div class="col-span-1">
                            <CommonFile @update:modelValue="simulateImportFile" label="Choose file" required
                                accepted-formats=".xls,.xlsx,.csv" />
                        </div>

                        <div class="col-span-1">
                            <CommonSelectAdd label="Status" placeholder="name" required routeName="employee.status.save"
                                inputName="name">
                                <CommonSelect selectClass="dark:!bg-transparent" v-model="form.status" class="!w-full" :options="status" optionLabel="name"
                                    :error="form.errors.status" />
                            </CommonSelectAdd>
                        </div>

                        <div class="col-span-1">
                            <CommonSelectAdd  label="Lead Source" placeholder="source" required
                                routeName="employee.source.save" inputName="source">
                                <CommonSelect  selectClass="dark:!bg-transparent" v-model="form.source" class="!w-full" :options="source" optionLabel="name"
                                    :error="form.errors.source" />
                            </CommonSelectAdd>
                        </div>

                        <div class="col-span-1 my-4 md:col-span-2 xl:col-span-3 flex gap-3 justify-end">
                            <CommonButton class="text-sm !py-1.5"  @click="handleSubmit" :processing="form.processing">Import</CommonButton>
                            <CommonButton variant="gray" class="text-sm !py-1.5"  @click="simulateImport">Simulate Import</CommonButton>
                        </div>
                    </div>
                </CommonCard>
            </div>
        </PanelLayout>
    </AppLayout>
</template>
