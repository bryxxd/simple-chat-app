<script setup>
import { cn } from "@/lib/utils";
import { chatVariants } from ".";

const props = defineProps({
    class: { type: null, required: false },
});
</script>
<template>
    <p :class="cn(chatVariants({ variant }), props.class)">
        <slot/>
    </p>
</template>