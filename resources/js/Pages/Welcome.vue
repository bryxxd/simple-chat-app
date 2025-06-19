<script>
import { Head } from '@inertiajs/vue3';
import LoginForm from '@/components/LoginForm.vue'
export default {
    components: {
        Head,
        LoginForm
    }
};
</script>

<template>
    <Head title="Welcome" />
    <div class="flex min-h-svh flex-col items-center justify-center bg-muted p-6 md:p-10">
        <div class="w-full max-w-sm md:max-w-3xl">
            <LoginForm />
        </div>
    </div>
</template>
