<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/Card";
import { Input, InputStatusMessage } from "@/Components/ui/customInput";
import { Label } from "@/Components/ui/Label";

const form = useForm({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("signup.store"));
};
</script>

<template>

    <Head title="Sign Up" />
    <GuestLayout>
        <Card class="mx-auto max-w-sm">
            <CardHeader>
                <CardTitle class="text-xl"> Sign Up </CardTitle>
                <CardDescription>
                    Enter your information to create an account
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form @submit.prevent="submit" method="POST" class="grid gap-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="grid gap-2">
                            <Label for="first-name">First name</Label>
                            <Input id="first-name" v-model="form.first_name" :class="{ 'border-red-500': form.errors.first_name }" />
                            <InputStatusMessage v-if="form.errors.first_name" :message="form.errors.first_name" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="last-name">Last name</Label>
                            <Input id="last-name" v-model="form.last_name" :class="{ 'border-red-500': form.errors.last_name }" />
                            <InputStatusMessage v-if="form.errors.last_name" :message="form.errors.last_name" />
                        </div>
                    </div>
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" v-model="form.email"
                            :class="{ 'border-red-500': form.errors.email }" />
                        <InputStatusMessage v-if="form.errors.email" :message="form.errors.email" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="password">Password</Label>
                        <Input id="password" type="password" v-model="form.password"
                            :class="{ 'border-red-500': form.errors.password }" />
                        <InputStatusMessage v-if="form.errors.password" :message="form.errors.password" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirm Password</Label>
                        <Input id="password_confirmation" type="password" v-model="form.password_confirmation" :class="{
                            'border-red-500': form.errors.password_confirmation,
                        }" />
                        <InputStatusMessage v-if="form.errors.password_confirmation" :message="form.errors.password_confirmation" />
                    </div>
                    <Button type="submit" class="w-full">
                        <span>Create an account</span>
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
                    Already have an account?
                    <Link :href="route('login.index')" class="underline">Sign in</Link>
                </div>
            </CardContent>
        </Card>
    </GuestLayout>
</template>
