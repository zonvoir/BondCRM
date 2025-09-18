<script setup>
import { Avatar, AvatarGroup } from 'primevue';



const props = defineProps({
    avatars: {
        type: Array,
        default: () => []
    },
    groupClass: { type: String, default: '' }
})
</script>

<template>
    <AvatarGroup :class="groupClass">
        <Avatar v-for="(av, idx) in avatars" :key="idx" :image="av.image" :label="av.label"
            :shape="av.shape || 'circle'" :size="av.size || 'normal'" :class="av.class" />
    </AvatarGroup>
</template>




<!-- use -->

<!-- <CommonAvatarGroups :avatars="[
                        { image: 'https://images.unsplash.com/photo-1502685104226-ee32379fefbe?w=200&h=200&fit=crop&crop=faces' },
                        { image: 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=200&h=200&fit=crop&crop=faces' },
                        { image: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&h=200&fit=crop&crop=faces' },
                        { image: 'https://images.unsplash.com/photo-1544723795-3fb6469f5b39?w=200&h=200&fit=crop&crop=faces' },
                        { image: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200&h=200&fit=crop&crop=faces' },
                        { label: '+2' }
                    ]" /> -->