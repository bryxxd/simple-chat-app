<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
    class: { type: null, required: false },
    variant: { type: null, required: false },
});
</script>
<template>
    <p :class="cn('bg-muted p-2 rounded-lg max-w-lg ', props.class)">
        <slot />
    </p>
</template>