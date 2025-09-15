<script setup>
import { ref, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
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
import CommonConfirmation from '@/Components/Common/CommonConfirmation.vue';
import { useSelectMapper } from '@/Composables/useSelectMapper.js';
import { useDebounce } from '@vueuse/core';
import CommonSelectAdd from '@/Components/Common/CommonSelectAdd.vue';
import CommonMultiTagInput from '@/Components/Common/CommonMultiTagInput.vue';

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
    id: null,
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

const params = route().params;
const op = ref();
const columns = ref();
const exportDropdownRef = ref();
const showDrawer = ref(false);
const isContactedToday = ref(false);
const deleteId = ref(null);
const openConfirmation = ref(false);
const isEdit = ref(false);
const selectedGridOption = ref('list');
const selectedSortOption = ref(params?.sort || 'desc');
const searchQuery = ref(params?.search || '');

const columnVisibility = ref({
    'Lead Name': true,
    Company: true,
    Email: true,
    Phone: true,
    Status: true,
    Source: true,
    Created: true,
    Action: true,
});

const handleDrawerOpen = () => {
    form.reset();
    showDrawer.value = true;
};

const handleDrawerClose = () => {
    form.reset();
    isEdit.value = false;
    showDrawer.value = false;
};

const debouncedSearchQuery = useDebounce(searchQuery, 500);

watch(debouncedSearchQuery, newValue => {
    const updatedParams = {
        ...params,
        search: newValue,
    };
    router.visit(route('employee.lead.index', updatedParams));
});

const handleSortBy = () => {
    selectedSortOption.value =
        selectedSortOption.value === 'asc' ? 'desc' : 'asc';
    updateSortInURL(selectedSortOption.value);
};

const handleExport = event => {
    exportDropdownRef.value.toggle(event);
};

const toggle = event => {
    op.value.popover.toggle(event);
};
const columnsDrawerToggle = event => {
    columns.value.popover.toggle(event);
};

const toggleColumnVisibility = columnName => {
    columnVisibility.value[columnName] = !columnVisibility.value[columnName];
};

const handleContactedToday = value => {
    form.isDateContacted = value;
    form.dateContacted = value === false ? null : form.dateContacted;
    isContactedToday.value = value;
};

const handleSubmit = () => {
    form.post(route('employee.lead.save'), {
        onSuccess: () => {
            handleDrawerClose();
        },
    });
};

const handleDestroy = id => {
    deleteId.value = id;
    openConfirmation.value = true;
};

const handleDestroyCancel = () => {
    openConfirmation.value = false;
    deleteId.value = null;
};

const handleDelete = () => {
    form.delete(
        route('employee.lead.destroy', {
            lead: deleteId.value,
        }),
        {
            onSuccess: () => {
                openConfirmation.value = false;
                deleteId.value = null;
            },
        }
    );
};

const handleEditLead = lead => {
    handleDrawerOpen();
    isEdit.value = true;
    const { mappedData: mappedCountry } = useSelectMapper(lead?.country, false);
    const { mappedData: mappedStatus } = useSelectMapper(lead?.status, false);
    const { mappedData: mappedSource } = useSelectMapper(lead?.source, false, {
        nameField: 'source',
        codeField: 'id',
    });
    form.id = lead?.id;
    form.source = mappedSource?.value;
    form.status = mappedStatus?.value;
    form.name = lead?.name;
    form.address = lead?.address;
    form.position = lead?.position;
    form.city = lead?.city;
    form.email = lead?.email;
    form.state = lead?.state;
    form.website = lead?.website;
    form.country = mappedCountry;
    form.phone = lead?.phone;
    form.zipCode = lead?.zip_code;
    form.leadValue = lead?.lead_value;
    form.description = lead?.description;
    form.public = lead?.public;
    form.isDateContacted = lead?.is_date_contacted;
    form.dateContacted = lead?.date_contacted;
    isContactedToday.value = lead?.is_date_contacted;
};

const updateSortInURL = sortValue => {
    const updatedParams = {
        ...params,
        sort: sortValue,
    };
    router.visit(route('employee.lead.index', updatedParams));
};

const filterStatus = status => {
    const updatedParams = {
        ...params,
        status: status,
    };
    return route('employee.lead.index', updatedParams);
};

const resetFilters = () => {
    router.visit(route('employee.lead.index'));
};

const exportLeads = type => {
    window.location.href = route('employee.lead.export', {
        ...props.params,
        type: type,
    });
};

const checkedRows = e => {
    console.log(e);
};

const handleRowClick = e => {
    router.visit(route('employee.lead.details', { lead: e.data?.id }));
};

const viewOptions = [
    { label: 'Grid', value: 'grid', icon: 'grid-view-outline-rounded' },
    { label: 'List', value: 'list', icon: 'lists-rounded' },
];

const exportMenu = [
    {
        label: 'Excel',
        class: 'text-sm',
        command: () => {
            exportLeads('excel');
        },
    },
    {
        label: 'CSV',
        class: 'text-sm',
        command: () => {
            exportLeads('csv');
        },
    },
    {
        label: 'PDF',
        class: 'text-sm',
        command: () => {
            exportLeads('pdf');
        },
    },
];

const leadColumns = [
    { name: 'Lead Name' },
    { name: 'Company' },
    { name: 'Email' },
    { name: 'Phone' },
    { name: 'Status' },
    { name: 'Source' },
    { name: 'Created' },
    { name: 'Action' },
];
</script>

<template>
    <AppLayout title="Lead">
        <PanelLayout>
            <div class="rounded-md bg-white p-5 shadow dark:bg-gray-800">
                <div>
                    <!-- filters  -->
                    <div
                        class="flex flex-nowrap gap-2 gap-y-5 overflow-x-auto py-5"
                    >
                        <div v-for="(s, index) in status" :key="index">
                            <Link :href="filterStatus(s?.code)">
                                <Badge
                                    class="max-w-fit cursor-pointer rounded-md p-2 px-2 text-xs font-normal text-nowrap"
                                    :style="{ backgroundColor: '#' + s.color }"
                                >
                                    {{ s?.leads_count }} {{ s.name }}
                                </Badge>
                            </Link>
                        </div>
                    </div>
                </div>
                <div
                    class="flex flex-wrap items-center justify-between gap-3 border-b pb-4"
                >
                    <div
                        class="flex w-full max-w-full items-center gap-2 sm:max-w-1/2 md:max-w-1/3"
                    >
                        <div>
                            <CommonInput
                                type="search"
                                v-model="searchQuery"
                                icon="heroicons:magnifying-glass"
                                placeholder="search"
                                InputClass="!py-2 "
                                class="w-full"
                            />
                        </div>

                        <div class="flex items-center">
                            <CommonButton
                                variant="gray"
                                class="!py- border text-sm"
                                @click="resetFilters"
                                v-tooltip="'Reset filters'"
                            >
                                <CommonIcon
                                    class="h-5 w-5"
                                    icon="qlementine-icons:funnel-crossed-16"
                                />
                            </CommonButton>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <div>
                            <CommonSelectButton
                                class="p-.5"
                                v-model="selectedGridOption"
                                :options="viewOptions"
                                optionLabel="label"
                                optionValue="value"
                            />
                        </div>
                        <CommonButton
                            @click="handleDrawerOpen"
                            class="h-fit text-sm"
                        >
                            <CommonIcon icon="heroicons:plus" class="h-4 w-4" />
                            Add Lead
                        </CommonButton>
                    </div>
                </div>

                <div v-if="selectedGridOption === 'list'">
                    <div class="flex flex-wrap justify-between border-b pb-4">
                        <div
                            class="flex items-center justify-between gap-2 py-4"
                        >
                            <div
                                class="dark:bg-dark flex items-center justify-center gap-2 bg-white"
                            >
                                <div
                                    class="transitions-colors cursor-pointer rounded-md border border-transparent bg-gray-100 p-2 text-gray-700 ring-offset-1 hover:bg-gray-200 focus-visible:ring-2 focus-visible:ring-indigo-600 active:text-gray-300"
                                >
                                    <CommonIcon
                                        @click="handleSortBy"
                                        :icon="
                                            selectedSortOption === 'asc'
                                                ? 'fa7-solid:arrow-up-wide-short'
                                                : 'fa7-solid:arrow-down-short-wide'
                                        "
                                    />
                                </div>
                            </div>
                            <div
                                class="dark:bg-dark flex items-center justify-center gap-2 bg-white"
                            >
                                <div>
                                    <CommonButton
                                        @click="handleExport"
                                        variant="gray"
                                        class="!py-2 text-sm"
                                    >
                                        Export
                                        <CommonIcon
                                            icon="heroicons:chevron-down"
                                        />
                                    </CommonButton>
                                    <CommonDropDown
                                        ref="exportDropdownRef"
                                        :items="exportMenu"
                                    />
                                </div>
                                <div>
                                    <Link :href="route('employee.lead.import')">
                                        <CommonButton
                                            variant="gray"
                                            class="border text-sm"
                                        >
                                            <CommonIcon
                                                class="h-5 w-5"
                                                icon="tabler:file-import"
                                            />
                                            import
                                        </CommonButton>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div>
                                <CommonButton
                                    variant="gray"
                                    class="relative border text-sm"
                                    @click="toggle"
                                >
                                    <Badge
                                        class="absolute -top-1 -right-1 rounded-full bg-indigo-600 p-1 text-[10px] text-white"
                                    >
                                    </Badge>
                                    <CommonIcon icon="heroicons:funnel" />
                                    Filters
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
                                            v-for="(
                                                column, index
                                            ) in leadColumns"
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
                                                        :class="
                                                            columnVisibility[
                                                                column.name
                                                            ]
                                                                ? 'text-green-500'
                                                                : 'text-gray-300'
                                                        "
                                                    />
                                                    <h4 class="text-sm">
                                                        {{ column.name }}
                                                    </h4>
                                                </div>
                                                <CommonToggleSwitch
                                                    :modelValue="
                                                        columnVisibility[
                                                            column.name
                                                        ]
                                                    "
                                                    @update:modelValue="
                                                        toggleColumnVisibility(
                                                            column.name
                                                        )
                                                    "
                                                    :disabled="
                                                        column.name === 'Action'
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </CommonPopover>
                            </div>
                        </div>
                    </div>
                    <CommonDataTable
                        checkbox
                        routeName="employee.lead.index"
                        :showSerialNumber="true"
                        :data="leads"
                        @update:modelSelection="checkedRows"
                        @rowClick="handleRowClick"
                    >
                        <Column
                            v-if="columnVisibility['Lead Name']"
                            field="name"
                            header="Lead Name"
                            :sortable="true"
                        />

                        <Column
                            v-if="columnVisibility['Company']"
                            field="company"
                            header="Company"
                            :sortable="true"
                        />
                        <Column
                            v-if="columnVisibility['Email']"
                            field="email"
                            header="Email"
                            :sortable="true"
                        />

                        <Column
                            v-if="columnVisibility['Phone']"
                            field="phone"
                            header="Phone"
                            :sortable="true"
                        />

                        <Column
                            v-if="columnVisibility['Status']"
                            header="Status"
                            sortField="status.name"
                            :sortable="true"
                        >
                            <template #body="slotProps">
                                <Badge
                                    class="max-w-fit cursor-pointer rounded-md p-2 px-2 text-xs font-normal"
                                    :style="{
                                        backgroundColor:
                                            '#' + slotProps.data?.status?.color,
                                    }"
                                >
                                    {{ slotProps.data?.status?.name }}
                                </Badge>
                            </template>
                        </Column>

                        <Column
                            v-if="columnVisibility['Source']"
                            header="Sources"
                            sortField="source.source"
                            :sortable="true"
                        >
                            <template #body="slotProps">
                                <CommonBadge
                                    :value="slotProps.data?.source?.source"
                                    severity="secondary"
                                />
                            </template>
                        </Column>

                        <Column
                            v-if="columnVisibility['Created']"
                            field="created_at"
                            header="Created"
                            :sortable="true"
                        />
                        <Column
                            v-if="columnVisibility['Action']"
                            header="Action"
                            :sortable="false"
                        >
                            <template #body="slotProps">
                                <div class="flex gap-2">
                                    <CommonButton
                                        type="button"
                                        @click="handleEditLead(slotProps?.data)"
                                        variant="secondary"
                                    >
                                        <CommonIcon icon="bi:pencil-square" />
                                    </CommonButton>

                                    <CommonButton
                                        @click="
                                            handleDestroy(slotProps?.data?.id)
                                        "
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
                <div v-else>Hello world</div>
            </div>

            <CommonModal
                v-model:visible="showDrawer"
                position="top"
                className="w-5xl"
                :header="isEdit ? 'Update Lead' : 'Create Lead'"
            >
                <div class="grid grid-cols-12 gap-4">
                    <!-- Lead Source -->
                    <div class="col-span-3">
                        <CommonSelectAdd
                            label="Lead Source"
                            placeholder="source"
                            required
                            routeName="employee.source.save"
                            inputName="source"
                        >
                            <CommonSelect
                                v-model="form.source"
                                :options="source"
                                optionLabel="name"
                                class="!w-full"
                                :error="form.errors.source"
                            />
                        </CommonSelectAdd>
                    </div>

                    <!-- Status -->
                    <div class="col-span-3">
                        <CommonSelectAdd
                            label="Status"
                            placeholder="name"
                            required
                            routeName="employee.status.save"
                            inputName="name"
                        >
                            <CommonSelect
                                v-model="form.status"
                                class="!w-full"
                                :options="status"
                                optionLabel="name"
                                :error="form.errors.status"
                            />
                        </CommonSelectAdd>
                    </div>

                    <!-- Name -->
                    <div class="col-span-6">
                        <CommonInput
                            v-model="form.name"
                            label="Name"
                            :error="form.errors.name"
                            placeholder="Name"
                            required
                        />
                    </div>
                    <div class="col-span-12">
                        <CommonMultiTagInput
                            labelClass="mb-1"
                            v-model="tags"
                            label="Tags"
                            separator=","
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
                                v-model="form.isDateContacted"
                                :onChange="handleContactedToday"
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
            <CommonConfirmation
                v-model="openConfirmation"
                title="Delete Confirmation"
                message="Are you sure you want to delete this item? This action cannot be undone."
                confirmText="Delete"
                cancelText="Cancel"
                @confirm="handleDelete"
                @cancel="handleDestroyCancel"
            />
        </PanelLayout>
    </AppLayout>
</template>
