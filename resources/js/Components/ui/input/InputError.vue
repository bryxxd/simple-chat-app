<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>
<template>
    <div :class="cn('text-red-500 text-sm', props.class)">
        <slot />
    </div>
</template>