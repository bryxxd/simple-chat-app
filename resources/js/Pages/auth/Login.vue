<script>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import LoginForm from '@/components/LoginForm.vue'
import SignUpForm from '@/components/SignUpForm.vue';
export default {
    components: {
        AppLayout,
        Head,
        LoginForm,
        SignUpForm
    }
};
</script>

<template>

    <Head title="Welcome" />
    <AppLayout>
        <LoginForm />
        <!-- <SignUpForm /> -->
    </AppLayout>

</template>
