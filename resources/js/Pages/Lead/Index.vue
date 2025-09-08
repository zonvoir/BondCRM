<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonModal from '@/Components/Common/CommonModal.vue';
import CommonIcon from '@/Components/Common/CommonIcon.vue';
import CommonDataTable from '@/Components/Common/CommonDataTable.vue';
import { Column } from 'primevue';
import CommonDropDown from '@/Components/Common/CommonDropDown.vue';
import CommonPopover from '@/Components/Common/CommonPopover.vue';
import CommonToggleSwitch from '@/Components/Common/CommonToggleSwitch.vue';
import CommonSelectButton from '@/Components/Common/CommonSelectButton.vue';
import CommonTextarea from '@/Components/Common/CommonTextarea.vue';
import CommonDatePicker from '@/Components/Common/CommonDatePicker.vue';
import CommonCheckbox from '@/Components/Common/CommonCheckbox.vue';
import CommonBadge from '@/Components/Common/CommonBadge.vue';

const props = defineProps({
    mailProviders: {
        type: Array,
    },
    scanAlgorithm: {
        type: Array,
    },
    status: {
        type: Array,
        default: () => [],
    },
    source: {
        type: Array,
        default: () => [],
    },
    countries: {
        type: Array,
        default: () => [],
    },
    leads: {
        type: Object,
    },
});

const form = useForm({
    name: '',
    source: '',
    status: '',
    address: '',
    position: '',
    city: '',
    email: '',
    state: '',
    website: '',
    country: '',
    phone: '',
    zipCode: '',
    leadValue: '',
    company: '',
    description: '',
    dateContacted: '',
    public: null,
    isDateContacted: null,
});

const op = ref();
const columns = ref();
const dropdownRef = ref();
const searchQuery = ref(null);
const showDrawer = ref(false);
const isContactedToday = ref(false);

const toggle = event => {
    op.value.popover.toggle(event);
};
const columnsDrawerToggle = event => {
    columns.value.popover.toggle(event);
};

const handleDrawerOpen = () => {
    showDrawer.value = true;
};

const handleDrawerClose = () => {
    showDrawer.value = false;
};

const mapLeads = {
    data: [
        {
            id: 1,
            lead_name: 'Amit G',
            company_name: 'Zonvoir Pvt Ltd',
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
const openMenu = event => {
    dropdownRef.value.toggle(event);
};

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

const handleSource = source => {
    form.source = source.code;
};

const handleStatus = status => {
    form.status = status.code;
};

const handleCountry = country => {
    form.country = country.code;
};

const handleContactedToday = () => {
    form.isDateContacted = true;
    isContactedToday.value = !isContactedToday.value;
};

const handleSubmit = () => {
    form.post(route('employee.lead.save'), {
        onSuccess: () => {
            handleDrawerClose();
        },
    });
};
</script>

<template>
    <AppLayout title="Lead">
        <PanelLayout>
            <div class="rounded-md bg-white p-5 shadow dark:bg-gray-800">
                <div>
                    <!-- filters  -->
                    <div class="flex flex-wrap gap-2 gap-y-5 py-5">
                        <div v-for="(s, index) in status" :key="index">
                            <Badge
                                class="max-w-fit cursor-pointer rounded-md p-2 px-2 text-xs font-normal"
                                :style="{ backgroundColor: '#' + s.color }"
                            >
                                {{ s?.leads_count }} {{ s.name }}
                            </Badge>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between border-b pb-4">
                    <div class="w-full max-w-full sm:max-w-1/2 md:max-w-1/3">
                        <CommonInput
                            v-model="searchQuery"
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
                                @click="openMenu"
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
                                @click="columnsDrawerToggle"
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
                    <CommonDataTable :showSerialNumber="true" :data="leads">
                        <Column
                            field="name"
                            header="Lead Name"
                            :sortable="true"
                        />

                        <Column
                            field="company"
                            header="Company"
                            :sortable="true"
                        />
                        <Column field="email" header="Email" :sortable="true" />

                        <Column field="phone" header="Phone" :sortable="true" />

                        <Column header="Status" :sortable="false">
                            <template #body="slotProps">
                                <div>
                                    <Badge
                                        class="max-w-fit cursor-pointer rounded-md p-2 px-2 text-xs font-normal"
                                        :style="{
                                            backgroundColor:
                                                '#' +
                                                slotProps.data?.status?.color,
                                        }"
                                    >
                                        {{ slotProps.data?.status?.name }}
                                    </Badge>
                                </div>
                            </template>
                        </Column>

                        <Column header="Sources" :sortable="true">
                            <template #body="slotProps">
                                <CommonBadge
                                    :value="slotProps.data?.source?.source"
                                    severity="secondary"
                                />
                            </template>
                        </Column>

                        <Column
                            field="created_at"
                            header="Created"
                            :sortable="true"
                        />
                        <Column header="Action" :sortable="false">
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <CommonButton
                                        type="button"
                                        variant="secondary"
                                    >
                                        <CommonIcon icon="bi:trash" />
                                    </CommonButton>
                                </div>
                            </template>
                        </Column>
                    </CommonDataTable>
                </div>
            </div>

            <CommonModal
                v-model:visible="showDrawer"
                position="top"
                className="w-7xl"
                header="Create Lead"
            >
                <div class="grid grid-cols-12 gap-4">
                    <!-- Lead Source -->
                    <div class="col-span-3">
                        <CommonSelect
                            @update:modelValue="handleSource"
                            label="Lead Source"
                            :options="source"
                            optionLabel="name"
                            :error="form.errors.source"
                            required
                        />
                    </div>

                    <!-- Status -->
                    <div class="col-span-3">
                        <CommonSelect
                            @update:modelValue="handleStatus"
                            label="Status"
                            :options="status"
                            optionLabel="name"
                            :error="form.errors.status"
                            required
                        />
                    </div>

                    <!-- Name -->
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.name"
                            label="Name"
                            :error="form.errors.name"
                            required
                        />
                    </div>

                    <!-- Address -->
                    <div class="col-span-12">
                        <CommonTextarea
                            v-model="form.address"
                            :error="form.errors.address"
                            label="Address"
                            rows="2"
                        />
                    </div>

                    <!-- Position & City -->
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.position"
                            label="Position"
                            :error="form.errors.position"
                        />
                    </div>
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.city"
                            label="City"
                            :error="form.errors.city"
                        />
                    </div>

                    <!-- Email & State -->
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.email"
                            label="Email"
                            type="email"
                            :error="form.errors.email"
                            required
                        />
                    </div>
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.state"
                            label="State"
                            :error="form.errors.state"
                        />
                    </div>

                    <!-- Website & Country -->
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.website"
                            label="Website"
                            :error="form.errors.website"
                        />
                    </div>
                    <div class="col-span-6">
                        <CommonSelect
                            @update:modelValue="handleCountry"
                            label="Country"
                            v-model="form.country"
                            :options="countries"
                            optionLabel="name"
                            :error="form.errors.country"
                        />
                    </div>

                    <!-- Phone & Zip Code -->
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.phone"
                            label="Phone"
                            type="number"
                            :error="form.errors.phone"
                        />
                    </div>
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.zipCode"
                            label="Zip Code"
                            type="number"
                            :error="form.errors.zipCode"
                        />
                    </div>

                    <!-- Lead Value & Company -->
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.leadValue"
                            label="Lead Value"
                            type="number"
                            :error="form.errors.leadValue"
                        />
                    </div>
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.company"
                            label="Company"
                            :error="form.errors.company"
                        />
                    </div>

                    <!-- Description -->
                    <div class="col-span-12">
                        <CommonTextarea
                            v-model="form.description"
                            :error="form.errors.description"
                            label="Description"
                            rows="3"
                        />
                    </div>

                    <!--Date Contacted-->
                    <div v-if="isContactedToday" class="col-span-12">
                        <CommonDatePicker
                            :showTime="true"
                            label="Date Contacted"
                            v-model="form.dateContacted"
                            :error="form.errors.dateContacted"
                        />
                    </div>
                    <div class="col-span-12 flex items-center gap-8">
                        <!-- Public -->
                        <label class="flex cursor-pointer items-center gap-2">
                            <CommonCheckbox v-model="form.public" />
                            <span>Public</span>
                        </label>

                        <!-- Contacted Today -->
                        <label class="flex cursor-pointer items-center gap-2">
                            <CommonCheckbox
                                @update:modelValue="handleContactedToday"
                            />
                            <span>Contacted Today</span>
                        </label>
                    </div>

                    <!-- Save Button -->
                    <div class="col-span-12 mt-4">
                        <CommonButton
                            @click="handleSubmit"
                            :processing="form.processing"
                            >Save Lead</CommonButton
                        >
                    </div>
                </div>
            </CommonModal>
        </PanelLayout>
    </AppLayout>
</template>
