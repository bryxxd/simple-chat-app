<script>
export default {
    name: "HeadingSmall",
    props: {
        title: {
            type: String,
            required: true
        },
        description: {
            type: String,
            default: ''
        }
    }
}
</script>

<template>
    <div>
        <h3 class="text-lg font-medium"> Profile </h3>
        <p class="text-sm text-muted-foreground"> This is how others will see you on the site. </p>
    </div>
</template>