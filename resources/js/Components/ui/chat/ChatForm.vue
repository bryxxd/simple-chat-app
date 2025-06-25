<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>
<template>
    <form :class="cn('flex relative', props.class)">
        <slot />
    </form>
</template>