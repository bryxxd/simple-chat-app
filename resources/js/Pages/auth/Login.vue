<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/Components/ui/Button';
import { Card, CardTitle, CardContent, CardHeader, CardDescription } from '@/Components/ui/Card';
import { Input, InputStatusMessage } from '@/Components/ui/customInput';
import { Label } from '@/Components/ui/Label';

const page = usePage();
const form = useForm({
    email: '',
    password: ''
});

const submit = () => {
    form.post(route('login.store'));
};
</script>

<template>

    <Head title="Login" />
    <GuestLayout>
        <div class="flex flex-col gap-6">
            <Card class="mx-auto max-w-sm">
                <CardHeader>
                    <CardTitle class="text-xl">
                        Login
                    </CardTitle>
                    <CardDescription>
                        Enter your email below to login to your account
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <InputStatusMessage v-if="page.props.flash?.success" :message="page.props.flash.success" variant="success" class="mb-6" />
                    <form @submit.prevent="submit" method="POST" class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="email">Email</Label>
                            <Input id="email" type="text" v-model="form.email"
                                :class="{ 'border-red-500': form.errors.email }" />
                            <InputStatusMessage v-if="form.errors.email" :message="form.errors.email" />
                        </div>
                        <div class="grid gap-2">
                            <div class="flex items-center">
                                <Label for="password">Password</Label>
                                <Link :href="route('password.request')" class="ml-auto inline-block text-sm underline">
                                Forgot your password?
                                </Link>
                            </div>
                            <Input id="password" type="password" v-model="form.password"
                                :class="{ 'border-red-500': form.errors.password }" />
                            <InputStatusMessage v-if="form.errors.password" :message="form.errors.password" />
                        </div>
                        <Button type="submit" class="w-full">
                            <span>Login</span>
                        </Button>
                        <div
                            class="relative text-center text-sm after:absolute after:inset-0 after:top-1/2 after:z-0 after:flex after:items-center after:border-t after:border-border">
                            <span class="relative z-10 bg-background px-2 text-muted-foreground">
                                Or signup with
                            </span>
                        </div>
                        <div class="flex justify-center">
                            <Button variant="outline" class="w-50" as="a" :href="route('auth.redirect')">
                                <img class="w-4" src="/images/google.png" alt="">
                                <span class="sr-only">Login with Google</span>
                            </Button>
                        </div>
                    </form>
                    <div class="mt-4 text-center text-sm">
                        Don't have an account?
                        <Link :href="route('signup.index')" class="underline">
                        Sign up
                        </Link>
                    </div>
                </CardContent>
            </Card>
            <div
                class="text-balance text-center text-xs text-muted-foreground [&_a]:underline [&_a]:underline-offset-4 hover:[&_a]:text-primary">
                By clicking continue, you agree to our
                <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
            </div>
        </div>
    </GuestLayout>

</template>
