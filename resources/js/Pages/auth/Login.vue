<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardTitle, CardContent, CardHeader, CardDescription } from '@/components/ui/card';
import { Input, InputStatusMessage } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const page = usePage();
const form = useForm({
    username: '',
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
                        Enter your username below to login to your account
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <InputStatusMessage v-if="page.props.flash?.success" :message="page.props.flash.success" variant="success" class="mb-6" />
                    <form @submit.prevent="submit" method="POST" class="grid gap-4">
                        <div class="grid gap-2">
                            <Label for="username">Username</Label>
                            <Input id="username" type="text" v-model="form.username"
                                :class="{ 'border-red-500': form.errors.username }" />
                            <InputStatusMessage v-if="form.errors.username" :message="form.errors.username" />
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
                            <Button variant="outline" class="w-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path
                                        d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"
                                        fill="currentColor" />
                                </svg>
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
