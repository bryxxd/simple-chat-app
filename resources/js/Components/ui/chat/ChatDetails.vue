<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>
<template>
    <div :class="cn('flex border-b py-4 bg-background', props.class)">
        <slot />
    </div>
</template>
