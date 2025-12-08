<script setup>
import Skeleton from "./Skeleton.vue";

const props = defineProps({
    class: { type: null, required: false },
});
</script>
<template>
    <div class="flex items-center space-x-4 w-full md:w-auto">
        <Skeleton class="h-14 w-14 rounded-full flex-shrink-0"/>
        <div class="space-y-2 grid grid-cols-1 md:block w-full md:w-auto">
            <Skeleton class="h-4 md:w-[250px]" />
            <Skeleton class="h-4 w-1/4 md:w-[200px]" />
        </div>
    </div>
</template>1