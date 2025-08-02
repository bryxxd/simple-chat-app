<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>
<template>
    <div :class="cn('px-4 pb-4 flex flex-col h-[calc(100svh-65px)]', props.class)">
        <slot />
    </div>
</template>