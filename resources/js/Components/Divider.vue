<script>
export default {
    name : "Divider",
    props : {
        orientation: {
            type: String,
            default: 'horizontal', // 'horizontal' or 'vertical'
        }
    }
}
</script>
<template>
    <div
        :data-orientation="orientation"
        :class="{
            'shrink-0 bg-border h-px w-full': orientation === 'horizontal',
            'shrink-0 bg-border w-px h-full': orientation === 'vertical',
        }"
        role="none"
    ></div>
</template>