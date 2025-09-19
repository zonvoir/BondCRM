<script setup>
import { Avatar } from 'primevue';


const props = defineProps({
  image: { type: String, default: '' },
  label: { type: String, default: '' },
  icon: { type: String, default: '' },
  size: { type: String, default: 'normal' },
  shape: { type: String, default: 'circle' },
  alt: { type: String, default: 'avatar' },
  class: { type: String, default: '' }
})
</script>

<template>
  <Avatar :image="image" :label="label" :size="size" :icon="icon" :shape="shape" :alt="alt" :class="class" />
</template>




<!-- use  -->

<!-- <CommonAvatar label="P" class="!bg-gray-200" size="xlarge" shape="circle" /> -->
