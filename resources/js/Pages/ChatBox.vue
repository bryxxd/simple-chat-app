<script>
export default {
    props: {
        selectedChat: {
            type: [Object, null],
            required: true,
            default: null
        }
    },
}

</script>
<template>
    <div class="aspect-video h-12 w-full rounded-lg bg-muted/50">
        <pre>
            {{ selectedChat }}
        </pre>
    </div>
</template>