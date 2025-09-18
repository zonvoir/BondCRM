<template>
    <div class="w-80 shrink-0 rounded-md bg-indigo-50  dark:bg-gray-800">
        <h2 class="mb-3 text-base flex bg-indigo-600 p-3 rounded-t-md  text-white items-center justify-between font-semibold"
            :class="`text-${color}-600`">
            {{ title }}
            <span class="text-sm text-gray-200 font-medium">{{ modelValue.length }} Leads</span>
        </h2>

        <div class="p-2 max-h-[80vh] overflow-y-auto no-scrollbar">
            <VueDraggable v-model="innerCards" group="kanban" :item-key="'id'" class="space-y-3 min-h-[20px]">
                <div v-for="item in innerCards" :key="item.id">
                    <CommonKabanCard :data="item" />
                </div>
            </VueDraggable>
        </div>
    </div>

</template>

<script setup>
import { ref, watch } from 'vue'
import { VueDraggable } from 'vue-draggable-plus'
import CommonKabanCard from './CommonKabanCard.vue'


const props = defineProps({
    title: String,
    color: String,
    modelValue: Array
})
const emit = defineEmits(['update:modelValue'])

const innerCards = ref([...props.modelValue])

watch(innerCards, val => emit('update:modelValue', val), { deep: true })
watch(() => props.modelValue, val => (innerCards.value = [...val]), { deep: true })
</script>
