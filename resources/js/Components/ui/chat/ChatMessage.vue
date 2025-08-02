<script setup>
import { cn } from "@/lib/utils";
import { chatMessageVariants } from ".";

const props = defineProps({
    class: { type: null, required: false },
    variant: { type: null, required: false },
});
</script>
<template>
    <p :class="cn(chatMessageVariants({ variant }), props.class)">
        <slot />
    </p>
</template>