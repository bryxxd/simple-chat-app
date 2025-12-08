<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>
<template>
    <div :class="cn('py-6 flex flex-col gap-y-[2rem] overflow-y-auto flex-1 min-h-0 flex-col-reverse', props.class)">
        <slot />
    </div>
</template>
