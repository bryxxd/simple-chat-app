<script setup>
import Skeleton from "./Skeleton.vue";

const props = defineProps({
    class: { type: null, required: false },
});

</script>
<template>
    
    <div class="flex gap-4 flex-row-reverse">
        <Skeleton class="h-8 w-8 rounded-full flex-shrink-0" />
        <Skeleton class="w-1/2 h-36 rounded-lg" />
    </div>

    <div class="flex gap-4 w-full md:w-auto">
        <Skeleton class="h-8 w-8 rounded-full flex-shrink-0" />
        <Skeleton class="w-1/2 h-36 rounded-lg" />
    </div>
</template>1
