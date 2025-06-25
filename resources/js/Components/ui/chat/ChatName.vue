<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>
<template>
    <span :class="cn('text-lg', props.class)">
        <slot/>
    </span>
</template>