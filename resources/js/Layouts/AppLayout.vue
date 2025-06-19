<script>
export default {
    name: 'AppLayout',
    props: {
        title: {
            type: String,
            default: 'Welcome',
        },
    },
};
</script>
<template>
    <div class="flex min-h-svh flex-col items-center justify-center p-6 md:p-10">
        <div class="w-full max-w-sm md:max-w-3xl">
            <slot />
        </div>
    </div>
</template>