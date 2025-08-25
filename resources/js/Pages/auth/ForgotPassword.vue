<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input, InputStatusMessage } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const form = useForm({
    email: ''
});

const submit = () => {
    form.post(route('password.email'), {
        onSuccess: () => {
            form.email = ''
        }
    });
};
</script>

<template>

    <Head title="Forgot Password" />
    <GuestLayout>
        <Card class="mx-auto max-w-sm">
            <CardHeader>
                <CardTitle class="text-xl">Forgot Password</CardTitle>
                <CardDescription>
                    Enter your email address and we will send you a link to reset your password.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" method="POST">
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="email" v-model="form.email"
                                :class="{ 'border-red-500': form.errors.email }" />
                            <InputStatusMessage v-if="form.errors.email" :message="form.errors.email" />
                            <InputStatusMessage v-if="page.props.flash?.success" :message="page.props.flash.success" variant="success" />
                        </div>
                        <Button type="submit" class="w-full">Send</Button>
                    </div>
                    <div class="mt-4 text-center text-sm">
                        <Link :href="route('login.index')" class="underline">Back to Login</Link>
                    </div>
                </form>
            </CardContent>
        </Card>
    </GuestLayout>

</template>
