<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/Components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/Card';
import { Input, InputStatusMessage } from '@/Components/ui/customInput';
import { Label } from '@/components/ui/Label';

const page = usePage();
const token = page.props.token;

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    token: token,
});

const submit = () => {
    form.post(route('password.store'), {
        onError: (error) => {
            console.log(error)
        },
        onSuccess: (page) => {
            console.log(page);
        }
    });
};
</script>

<template>

    <Head title="Reset Password" />
    <GuestLayout>
        <Card class="mx-auto max-w-sm">
            <CardHeader>
                <CardTitle class="text-xl">Reset Password</CardTitle>
                <CardDescription>
                    Enter your email address and your new password to reset your password.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" method="POST">
                    <input type="hidden" name="token" :value="token">
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="email" v-model="form.email"
                                :class="{ 'border-red-500': form.errors.email }" />
                            <InputStatusMessage v-if="form.errors.email" :message="form.errors.email" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="password">New Password</Label>
                            <Input id="password" type="password" v-model="form.password"
                                :class="{ 'border-red-500': form.errors.password }" />
                            <InputStatusMessage v-if="form.errors.password" :message="form.errors.password" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="password_confirmation">Confirm Password</Label>
                            <Input id="password_confirmation" type="password" v-model="form.password_confirmation"
                                :class="{ 'border-red-500': form.errors.password_confirmation }" />
                            <InputStatusMessage v-if="form.errors.password_confirmation"
                                :message="form.errors.password_confirmation" />
                        </div>
                        <Button type="submit" class="w-full">Change Password</Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    </GuestLayout>

</template>
