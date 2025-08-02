<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
  src : {
    type: String,
    required: false
  }
});
</script>
<template>
    <img :class="cn('rounded-full w-14 h-14', props.class)" :src="props.src"/>
</template>