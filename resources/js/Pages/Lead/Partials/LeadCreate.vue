<script setup>
import { onMounted, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CommonSelectAdd from '@/Components/Common/CommonSelectAdd.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonMultiTagInput from '@/Components/Common/CommonMultiTagInput.vue';
import CommonTextarea from '@/Components/Common/CommonTextarea.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonCheckbox from '@/Components/Common/CommonCheckbox.vue';
import CommonDatePicker from '@/Components/Common/CommonDatePicker.vue';
import { useSelectMapper } from '@/Composables/useSelectMapper.js';

const props = defineProps({
    isEdit: { type: Boolean, default: false },
    editLead: { type: Object, default: null },
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
    tags: {
        type: Array,
    },
});

const emit = defineEmits(['saved', 'close']);
const isContactedToday = ref(false);

const form = useForm({
    id: null,
    name: '',
    tags: null,
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

const fillFromEdit = lead => {
    const { mappedData: mappedCountry } = useSelectMapper(lead.country, false);
    const { mappedData: mappedStatus } = useSelectMapper(lead.status, false);
    const { mappedData: mappedSource } = useSelectMapper(lead.source, false, {
        nameField: 'source',
        codeField: 'id',
    });
    form.id = lead.id;
    form.source = mappedSource?.value;
    form.status = mappedStatus?.value;
    form.name = lead.name;
    form.tags = lead.tags;
    form.address = lead.address;
    form.position = lead.position;
    form.city = lead.city;
    form.email = lead.email;
    form.state = lead.state;
    form.website = lead.website;
    form.country = mappedCountry;
    form.phone = lead.phone;
    form.zipCode = lead.zip_code;
    form.leadValue = lead.lead_value;
    form.description = lead.description;
    form.public = lead.public === true ? 'public' : 'private';
    form.isDateContacted = lead.is_date_contacted;
    form.dateContacted = lead.date_contacted;
    isContactedToday.value = lead.is_date_contacted;
};

onMounted(() => {
    if (props.isEdit && props?.editLead) {
        fillFromEdit(props.editLead);
    } else {
        handleDrawerClose();
    }
});
const handleContactedToday = value => {
    form.isDateContacted = value;
    form.dateContacted = value === false ? null : form.dateContacted;
    isContactedToday.value = value;
};

const handleDrawerClose = () => {
    form.reset();
};

const handleSubmit = () => {
    form.post(route('employee.lead.save'), {
        onSuccess: () => {
            handleDrawerClose();
            emit('saved');
        },
    });
};
</script>

<template>
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
                :suggestionOptions="tags"
                labelClass="mb-1"
                v-model="form.tags"
                label="Tags"
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
            <div class="flex flex-row gap-2">
                <label class="flex cursor-pointer items-center gap-2">
                    <input
                        type="radio"
                        name="bulk-visibility"
                        id="bulk-public"
                        value="public"
                        v-model="form.public"
                    />
                    <span>Public</span>
                </label>

                <label class="flex cursor-pointer items-center gap-2">
                    <input
                        type="radio"
                        name="bulk-visibility"
                        id="bulk-private"
                        value="private"
                        v-model="form.public"
                    />
                    <span>Private</span>
                </label>
            </div>

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
            <CommonButton @click="handleSubmit" :processing="form.processing"
                >Save Lead</CommonButton
            >
        </div>
    </div>
</template>
