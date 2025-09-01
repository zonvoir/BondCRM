<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CommonCard from '@/Components/Common/CommonCard.vue';
import CommonInput from '@/Components/Common/CommonInput.vue';
import CommonButton from '@/Components/Common/CommonButton.vue';
import CommonSelect from '@/Components/Common/CommonSelect.vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import PanelLayout from '@/Layouts/PanelLayout.vue';

const props = defineProps({
    liveChatSettings: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <AppLayout title="Settings">
        <PanelLayout>
            <CommonCard> </CommonCard>
        </PanelLayout>
    </AppLayout>
</template>
