<script setup>
import { cn } from "@/lib/utils";
import { inputMessageVariant } from ".";

const props = defineProps({
  variant: { type: null, required: false },
  class: { type: null, required: false },
  message: { type: String, required: false }
});
</script>
<template>
  <div :class="cn(inputMessageVariant({ variant }), props.class)">
    {{ props.message }}
  </div>
</template>
