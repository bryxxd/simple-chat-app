<script>
export default {
    props: {
        activeChat: {
            type: [Object, null],
            required: true,
            default: null
        }
    },
    name: "ChatBox"
}

</script>
<template>
    <div class="aspect-video h-12 w-full rounded-lg bg-muted/50">
        <div>
            {{ activeChat }}
        </div>
    </div>
</template>