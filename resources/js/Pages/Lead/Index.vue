<script setup>
import { ref, watch } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { useDebounce } from '@vueuse/core';
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
import CommonDatePicker from '@/Components/Common/CommonDatePicker.vue';
import CommonCheckbox from '@/Components/Common/CommonCheckbox.vue';
import CommonBadge from '@/Components/Common/CommonBadge.vue';
import CommonConfirmation from '@/Components/Common/CommonConfirmation.vue';
import CommonMultiTagInput from '@/Components/Common/CommonMultiTagInput.vue';
import CommonDrawer from '@/Components/Common/CommonDrawer.vue';
import LeadCreate from '@/Pages/Lead/Partials/LeadCreate.vue';
import SocialSync from '@/Pages/Lead/Partials/SocialSync.vue';

const props = defineProps({
    scanAlgorithm: {
        type: Array,
        default: () => [],
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
    tags: {
        type: Array,
    },
});

const bulkForm = useForm({
    ids: null,
    tags: null,
    isDelete: false,
    markLost: false,
    status: null,
    source: null,
    lastContact: null,
    type: null,
});

const params = route().params;
const op = ref();
const columns = ref();
const exportDropdownRef = ref();
const showLeadModal = ref(false);
const editLead = ref(null);
const isEdit = ref(false);
const showSyncModal = ref(false);
const showBulkModal = ref(false);
const deleteId = ref(null);
const openConfirmation = ref(false);
const selectedGridOption = ref('list');
const selectedSortOption = ref(params?.sort || 'desc');
const searchQuery = ref(params?.search || '');
const selectRowIDs = ref(null);

const columnVisibility = ref({
    'Lead Name': true,
    Company: true,
    Email: true,
    Phone: true,
    Tags: true,
    Status: true,
    Source: true,
    Created: true,
    Action: true,
});

const handleDrawerOpen = () => {
    showLeadModal.value = true;
};

const handleDrawerClose = () => {
    showLeadModal.value = false;
    isEdit.value = false;
    editLead.value = null;
};
const handleSyncDrawerOpen = () => {
    showSyncModal.value = true;
};

const handleSyncClose = () => {
    showSyncModal.value = false;
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
    isEdit.value = true;
    editLead.value = lead;
    handleDrawerOpen();
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
    const ids = e?.map(item => item?.id);
    selectRowIDs.value = ids;
    bulkForm.ids = ids;
};

const handleBulkModal = () => {
    resetBulkForm();
    showBulkModal.value = true;
};
const handleBulkAction = () => {
    if (bulkForm.isDelete || bulkForm.markLost) {
        showBulkModal.value = false;
        openConfirmation.value = true;
    } else {
        submitBulkAction();
    }
};

const submitBulkAction = () => {
    bulkForm.post(route('employee.lead.bulk.action'), {
        onSuccess: () => {
            showBulkModal.value = false;
            resetBulkForm();
        },
    });
};

const handleBulkConfirm = () => {
    openConfirmation.value = false;
    submitBulkAction();
};

const handleBulkCancel = () => {
    openConfirmation.value = false;
};

const resetBulkForm = () => {
    const ids = [...bulkForm.ids];
    bulkForm.reset();
    bulkForm.ids = ids;
};

const handleRowClick = e => {
    // router.visit(route('employee.lead.details', { lead: e.data?.id }));
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
    { name: 'Tags' },
    { name: 'Status' },
    { name: 'Source' },
    { name: 'Created' },
    { name: 'Action' },
];
const responsiveOptions = [
    {
        breakpoint: '2100px',
        numVisible: 8,
        numScroll: 1,
    },
    {
        breakpoint: '1920px',
        numVisible: 6,
        numScroll: 1,
    },
    {
        breakpoint: '1440px',
        numVisible: 5,
        numScroll: 1,
    },
    {
        breakpoint: '1366px',
        numVisible: 4,
        numScroll: 1,
    },
    {
        breakpoint: '1280px',
        numVisible: 4,
        numScroll: 1,
    },
    {
        breakpoint: '768px',
        numVisible: 3,
        numScroll: 1,
    },
    {
        breakpoint: '412px',
        numVisible: 1,
        numScroll: 1,
    },
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
                                InputClass="!py-1 "
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
                                    class="h-4 w-4"
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
                            variant="gray"
                            @click="handleSyncDrawerOpen"
                            class="h-fit !py-2 text-sm"
                        >
                            <CommonIcon
                                icon="material-symbols:bigtop-updates"
                                class="h-4 w-4"
                            />
                        </CommonButton>
                        <CommonButton
                            @click="handleDrawerOpen"
                            class="h-fit !py-2 text-sm"
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
                                    @click="handleSortBy"
                                    class="transitions-colors cursor-pointer rounded-md border border-transparent bg-gray-100 p-2 text-gray-700 ring-offset-1 hover:bg-gray-200 focus-visible:ring-2 focus-visible:ring-indigo-600 active:text-gray-300"
                                >
                                    <CommonIcon
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
                                <div>
                                    <CommonButton
                                        :disabled="!selectRowIDs?.length"
                                        @click="handleBulkModal"
                                        variant="gray"
                                        class="border text-sm"
                                    >
                                        <CommonIcon
                                            class="h-5 w-5"
                                            icon="tdesign:folder-setting-filled"
                                        />
                                        Bulk Action
                                    </CommonButton>
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
                            v-if="columnVisibility['Tags']"
                            field="tags"
                            header="Tags"
                            :sortable="true"
                        >
                            <template #body="slotProps">
                                <div class="flex flex-col gap-1">
                                    <CommonBadge
                                        v-for="tag in slotProps.data?.tags.slice(
                                            0,
                                            2
                                        )"
                                        :key="tag.id"
                                        class="mr-1"
                                        :value="tag"
                                    />

                                    <div
                                        v-if="slotProps.data?.tags.length > 2"
                                        class="cursor-pointer rounded-md px-2 py-1 text-xs font-normal text-gray-700"
                                    >
                                        +{{ slotProps.data?.tags.length - 2 }}
                                    </div>
                                </div>
                            </template>
                        </Column>

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
                                        backgroundColor: `#${slotProps.data?.status?.color}30`,
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
                                        variant="editBtn"
                                    >
                                        <CommonIcon icon="bi:pencil-square" />
                                    </CommonButton>

                                    <CommonButton
                                        @click="
                                            handleDestroy(slotProps?.data?.id)
                                        "
                                        type="button"
                                        variant="deleteBtn"
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
                @afterHide="handleDrawerClose"
                v-model:visible="showLeadModal"
                position="top"
                className="w-5xl"
                :header="isEdit ? 'Update Lead' : 'Create Lead'"
            >
                <LeadCreate
                    :isEdit="isEdit"
                    :editLead="editLead"
                    :status="status"
                    :countries="countries"
                    :source="source"
                    :tags="tags"
                    @saved="handleDrawerClose"
                />
            </CommonModal>

            <CommonDrawer
                v-model:visible="showSyncModal"
                header="Social Sync With AI"
            >
                <SocialSync
                    :handleSyncClose="handleSyncClose"
                    :status="status"
                    :scanAlgorithm="scanAlgorithm"
                />
            </CommonDrawer>

            <CommonModal
                v-model:visible="showBulkModal"
                position="top"
                header="Bulk actions"
            >
                <div>
                    <!-- Actions -->
                    <fieldset class="space-y-3">
                        <legend
                            class="text-sm font-medium text-gray-900 dark:text-gray-100"
                        >
                            Actions
                        </legend>

                        <!-- Delete (destructive) -->
                        <label class="flex items-center gap-2">
                            <CommonCheckbox
                                :disabled="bulkForm.markLost"
                                v-model="bulkForm.isDelete"
                                aria-describedby="bulk-delete-help"
                            />
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300"
                                >Delete</span
                            >
                        </label>
                        <p
                            id="bulk-delete-help"
                            class="text-xs text-red-600"
                        ></p>
                    </fieldset>

                    <div class="space-y-5">
                        <!-- Mark as lost -->
                        <label class="flex items-center gap-2">
                            <CommonCheckbox
                                :id="'bulk-lost'"
                                v-model="bulkForm.markLost"
                                :disabled="bulkForm.isDelete"
                                aria-describedby="bulk-lost-help"
                            />
                            <span
                                class="text-sm text-gray-700 dark:text-gray-300"
                                >Mark as lost</span
                            >
                        </label>
                        <p id="bulk-lost-help" class="text-xs text-gray-500">
                            Flags leads as lost without deleting them.
                        </p>

                        <!-- Fields -->
                        <fieldset class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <legend class="sr-only">Bulk field changes</legend>

                            <CommonSelect
                                :id="'bulk-status'"
                                label="Change status"
                                v-model="bulkForm.status"
                                class="!w-full"
                                :options="status"
                                optionLabel="name"
                                :disabled="
                                    bulkForm.isDelete || bulkForm.markLost
                                "
                                :error="bulkForm.errors.status"
                            />

                            <CommonSelect
                                :id="'bulk-source'"
                                label="Lead source"
                                v-model="bulkForm.source"
                                class="!w-full"
                                :options="source"
                                :disabled="bulkForm.isDelete"
                                optionLabel="name"
                                :error="bulkForm.errors.source"
                            />

                            <CommonDatePicker
                                :showTime="true"
                                :id="'bulk-last-contact'"
                                label="Last contact"
                                :disabled="bulkForm.isDelete"
                                v-model="bulkForm.lastContact"
                                :error="bulkForm.errors.lastContact"
                            />

                            <div class="col-span-12">
                                <CommonMultiTagInput
                                    :suggestionOptions="tags"
                                    labelClass="mb-1"
                                    v-model="bulkForm.tags"
                                    label="Tags"
                                />
                            </div>
                        </fieldset>

                        <fieldset class="space-y-2">
                            <label
                                class="flex cursor-pointer items-center gap-2"
                            >
                                <input
                                    :disabled="bulkForm.isDelete"
                                    type="radio"
                                    name="bulk-visibility"
                                    id="bulk-public"
                                    value="public"
                                    v-model="bulkForm.type"
                                />
                                <span>Public</span>
                            </label>

                            <label
                                class="flex cursor-pointer items-center gap-2"
                            >
                                <input
                                    :disabled="bulkForm.isDelete"
                                    type="radio"
                                    name="bulk-visibility"
                                    id="bulk-private"
                                    value="private"
                                    v-model="bulkForm.type"
                                />
                                <span>Private</span>
                            </label>
                        </fieldset>
                    </div>

                    <div class="my-5 flex justify-between gap-2">
                        <CommonButton
                            size="xs"
                            variant="danger"
                            @click="showBulkModal = false"
                            >Close</CommonButton
                        >
                        <CommonButton @click="handleBulkAction" size="xs"
                            >Confirm</CommonButton
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
            <CommonConfirmation
                v-model="openConfirmation"
                title="Confirm Bulk Action"
                message="Are you sure you want to proceed with this bulk action? This cannot be undone."
                confirmText="Yes, Proceed"
                cancelText="Cancel"
                @confirm="handleBulkConfirm"
                @cancel="handleBulkCancel"
            />
        </PanelLayout>
    </AppLayout>
</template>
