<script setup>
import { useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonDrawer from '@/Components/Common/CommonDrawer.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonDatePicker from '@/Components/Common/CommonDatePicker.vue';
import CommonModal from '@/Components/Common/CommonModal.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonDataTable from '@/Components/Common/CommonDataTable.vue';
import { Column } from 'primevue';
import CommonDropDown from '@/Components/Common/CommonDropDown.vue';
import CommonPopover from '@/Components/Common/CommonPopover.vue';
import CommonToggleSwitch from '@/Components/Common/CommonToggleSwitch.vue';
import CommonSelectButton from '@/Components/Common/CommonSelectButton.vue';

const op = ref();
const columns = ref();

const toggle = event => {
    op.value.popover.toggle(event);
};
const columnsDrawerToggle = event => {
    columns.value.popover.toggle(event);
};

const props = defineProps({
    mailProviders: {
        type: Array,
    },
    scanAlgorithm: {
        type: Array,
    },
});

const searchQuery = ref(null);
const showDrawer = ref(false);
const isDateDisabled = ref();

const form = useForm({
    provider: '',
    algorithm: '',
    startDate: '',
    endDate: '',
});

const handleDrawerOpen = () => {
    showDrawer.value = true;
};

const handleDrawerClose = () => {
    showDrawer.value = false;
};

const selectedProvider = e => {
    isDateDisabled.value = !['gmail', 'outlook'].includes(e?.code);
    form.provider = e;
};

const mapItems = [
    { lable: 'New', count: 8, percentage: null, class: 'bg-gray-100 ' },
    { lable: 'Contacted', count: 10, percentage: null, class: 'bg-gray-100  ' },
    { lable: 'Qualified', count: 9, percentage: null, class: 'bg-gray-100   ' },
    { lable: 'Working', count: 9, percentage: null, class: 'bg-gray-100  ' },
    {
        lable: 'Proposal Sent',
        count: 7,
        percentage: null,
        class: 'bg-gray-100  ',
    },
    {
        lable: 'Customer',
        count: 7,
        percentage: null,
        class: 'bg-lime-100 text-lime-600',
    },
    {
        lable: 'Lost Leads',
        count: 0,
        percentage: '0.00%',
        class: 'bg-red-100  text-red-500 ',
    },
];

const mapLeads = {
    data: [
        {
            id: 1,
            lead_name: 'Amit Sharma',
            company_name: 'TechSoft Pvt Ltd',
            phone: '+91 9876543210',
            lead_status: 'Rahul Verma',
            created_at: '2025-09-01',
        },
        {
            id: 2,
            lead_name: 'Priya Mehta',
            company_name: 'NextGen Solutions',
            phone: '+91 8765432109',
            lead_status: 'Anjali Singh',
            created_at: '2025-08-29',
        },
        {
            id: 3,
            lead_name: 'Ravi Kumar',
            company_name: 'Skyline Corp',
            phone: '+91 7654321098',
            lead_status: 'Suresh Gupta',
            created_at: '2025-08-25',
        },
        {
            id: 4,
            lead_name: 'Sneha Rani',
            company_name: 'WebX Labs',
            phone: '+91 6543210987',
            lead_status: 'Nitin Joshi',
            created_at: '2025-08-20',
        },
        {
            id: 5,
            lead_name: 'Arjun Yadav',
            company_name: 'SmartTech Global',
            phone: '+91 9123456780',
            lead_status: 'Vikas Sharma',
            created_at: '2025-08-18',
        },
        {
            id: 6,
            lead_name: 'Neha Kapoor',
            company_name: 'CloudByte Inc',
            phone: '+91 9988776655',
            lead_status: 'Manish Rao',
            created_at: '2025-08-15',
        },
        {
            id: 7,
            lead_name: 'Vivek Sinha',
            company_name: 'Bright Future Ltd',
            phone: '+91 8899776655',
            lead_status: 'Rohit Bansal',
            created_at: '2025-08-10',
        },
        {
            id: 8,
            lead_name: 'Divya Agarwal',
            company_name: 'NextTech AI',
            phone: '+91 7788996655',
            lead_status: 'Meena Kumari',
            created_at: '2025-08-05',
        },
        {
            id: 9,
            lead_name: 'Karan Patel',
            company_name: 'Global Vision',
            phone: '+91 6677889900',
            lead_status: 'Shweta Malhotra',
            created_at: '2025-08-02',
        },
        {
            id: 10,
            lead_name: 'Ankita Joshi',
            company_name: 'InnoWorks',
            phone: '+91 9988007766',
            lead_status: 'Deepak Yadav',
            created_at: '2025-07-30',
        },
    ],
};

const selectRows = e => {
    selectForm.ids = e?.map(item => item?.id);
};

const handleDestroy = id => {
    deleteId.value = id;
    openConfirmation.value = true;
};

const dropdownRef = ref();

function openMenu(event) {
    dropdownRef.value.toggle(event);
}

const menuItems = [
    {
        label: 'Newest',
        class: 'text-sm',
        command: () => console.log('Profile clicked'),
    },
    {
        label: 'Oldest',
        class: 'text-sm',
        command: () => console.log('Settings clicked'),
    },
];

const leadColumns = [
    { name: 'Lead Name' },
    { name: 'Company Name' },
    { name: 'Phone' },
    { name: 'Lead Owner' },
    { name: 'Created At' },
    { name: 'Action' },
];

const viewOptions = [
    { label: 'Grid', value: 'grid', icon: 'grid-view-outline-rounded' },
    { label: 'List', value: 'list', icon: 'lists-rounded' },
];
</script>

<template>
    <AppLayout title="Lead">
        <PanelLayout>
            <div class="rounded-md bg-white p-5 shadow dark:bg-gray-800">
                <div>
                    <h1
                        class="flex items-center gap-2 text-lg font-semibold text-gray-700 dark:text-gray-200"
                    >
                        Leads
                        <Badge
                            class="text-dark rounded-md bg-indigo-200 p-1 text-sm shadow-md dark:bg-indigo-600 dark:text-white"
                        >
                            123</Badge
                        >
                    </h1>

                    <!-- filters  -->
                    <div class="flex flex-wrap gap-2 gap-y-5 py-5">
                        <div v-for="(item, index) in mapItems" :key="index">
                            <Badge
                                :class="[
                                    'max-w-fit rounded-md p-2 px-2 text-xs font-normal',
                                    item.class,
                                ]"
                                >{{ item.count }} {{ item.lable }}</Badge
                            >
                        </div>
                    </div>
                </div>
                <div class="flex justify-between border-b pb-4">
                    <div class="w-full max-w-full sm:max-w-1/2 md:max-w-1/3">
                        <CommonInput
                            icon="heroicons:magnifying-glass"
                            placeholder="search"
                            InputClass="!py-2 "
                            class="w-full"
                        />
                    </div>
                    <CommonButton
                        @click="handleDrawerOpen"
                        class="h-fit text-sm"
                    >
                        <CommonIcon icon="heroicons:plus" class="h-4 w-4" /> Add
                        Lead
                    </CommonButton>
                </div>
                <div class="flex items-center justify-between py-4">
                    <div
                        class="dark:bg-dark flex items-center justify-center gap-2 bg-white"
                    >
                        <div>
                            <CommonButton
                                ref="buttonRef"
                                @click="openMenu($event)"
                                variant="gray"
                                class="!py-2 text-sm"
                            >
                                <CommonIcon icon="heroicons:bars-arrow-down" />
                                Sort by
                                <CommonIcon icon="heroicons:chevron-down" />
                            </CommonButton>
                            <CommonDropDown
                                ref="dropdownRef"
                                :items="menuItems"
                            />
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div>
                            <CommonButton
                                variant="gray"
                                class="relative border text-sm"
                                @click="toggle($event)"
                            >
                                <Badge
                                    class="absolute -top-1 -right-1 rounded-full bg-indigo-600 p-1 text-[10px] text-white"
                                >
                                </Badge>
                                <CommonIcon icon="heroicons:funnel" /> Filters
                            </CommonButton>

                            <CommonPopover ref="op">
                                this is popover
                            </CommonPopover>
                        </div>
                        <div>
                            <CommonButton
                                variant="primary"
                                class="relative border text-sm"
                                @click="columnsDrawerToggle($event)"
                            >
                                Manage Columns
                            </CommonButton>

                            <CommonPopover ref="columns" unstyled>
                                <div
                                    class="mt-2 max-h-80 overflow-y-auto rounded-md border shadow-xl"
                                >
                                    <div
                                        v-for="(value, index) in leadColumns"
                                        :key="index"
                                        :class="[
                                            index % 2 === 0
                                                ? 'bg-white'
                                                : 'bg-gray-100',
                                        ]"
                                        class="min-w-xs"
                                    >
                                        <div
                                            class="flex items-center justify-between p-3"
                                        >
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <CommonIcon
                                                    icon="material-symbols:circle"
                                                    class="h-2 w-2"
                                                />
                                                <h4 class="text-sm">
                                                    {{ value.name }}
                                                </h4>
                                            </div>
                                            <CommonToggleSwitch />
                                        </div>
                                    </div>
                                </div>
                            </CommonPopover>
                        </div>
                        <div
                            class="flex items-center gap-2 rounded-md bg-gray-100"
                        >
                            <CommonSelectButton
                                v-model="selectedView"
                                :options="viewOptions"
                                optionLabel="label"
                                optionValue="value"
                            >
                                <template #option="{ option }">
                                    <div class="flex items-center">
                                        <CommonIcon
                                            :icon="`material-symbols:${option.icon}`"
                                            class="h-5 w-5"
                                        />
                                    </div>
                                </template>
                            </CommonSelectButton>
                        </div>
                    </div>
                </div>

                <div>
                    <CommonDataTable
                        :showSerialNumber="true"
                        :data="mapLeads"
                        checkbox
                        @update:modelSelection="selectRows"
                    >
                        <Column
                            field="lead_name"
                            header="Lead Name"
                            :sortable="true"
                        />
                        <Column
                            field="company_name"
                            header="Company Name"
                            :sortable="true"
                        />
                        <Column field="phone" header="Phone" :sortable="true" />
                        <Column
                            field="lead_status"
                            header="Lead Owner"
                            :sortable="true"
                        />
                        <Column
                            field="created_at"
                            header="Created At"
                            :sortable="true"
                        />
                        <Column header="Action" :sortable="false">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <CommonButton
                                        type="button"
                                        @click="
                                            handleDestroy(slotProps?.data?.id)
                                        "
                                        variant="secondary"
                                    >
                                        <CommonIcon icon="bi:trash" />
                                    </CommonButton>
                                </div>
                            </template>
                        </Column>
                    </CommonDataTable>
                </div>

                <CommonModal v-model:visible="showDrawer" header="Create Lead">
                    <div
                        class="mt-2 mb-3 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-900/40 dark:text-blue-300"
                    >
                        💡 <span class="font-medium">Note:</span> Deep Scan
                        option is available only for
                        <span class="font-semibold">Gmail</span> and
                        <span class="font-semibold">Outlook</span>.
                    </div>

                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-6">
                            <CommonDatePicker
                                v-model="form.startDate"
                                label="Start Date"
                                :disabled="isDateDisabled"
                            />
                        </div>
                        <div class="col-span-6">
                            <CommonDatePicker
                                v-model="form.endDate"
                                label="End Date"
                                :disabled="isDateDisabled"
                            />
                        </div>

                        <div class="col-span-12">
                            <CommonSelect
                                @update:modelValue="selectedProvider"
                                label="Lead Source"
                                :options="mailProviders"
                                optionLabel="name"
                                :error="form.errors.provider"
                                required
                            />
                        </div>

                        <div class="col-span-12">
                            <CommonSelect
                                v-model="form.algorithm"
                                label="Scan Algorithm"
                                :options="scanAlgorithm"
                                optionLabel="name"
                                :error="form.errors.algorithm"
                                required
                            />
                        </div>
                    </div>
                    <template #footer>
                        <div class="flex justify-between gap-2">
                            <CommonButton
                                variant="danger"
                                @click="handleDrawerClose"
                                >Close</CommonButton
                            >
                            <CommonButton>Save</CommonButton>
                        </div>
                    </template>
                </CommonModal>
            </div>
        </PanelLayout>
    </AppLayout>
</template>
