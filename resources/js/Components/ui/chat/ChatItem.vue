<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
    class: { type: null, required: false },
});
</script>
<template>
    <div :class="cn('flex', props.class)">
        <slot />
    </div>
</template>