<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Button } from "@/Components/ui/Button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/Card";
import { InputStatusMessage } from "@/Components/ui/customInput";

const page = usePage();

</script>

<template>

    <Head title="Verify Email" />
    <GuestLayout>
        <Card class="mx-auto max-w-sm">
            <CardHeader>
                <CardTitle class="text-xl">Reset Password</CardTitle>
                <CardDescription>
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on
                    the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.
                </CardDescription>
            </CardHeader>

            <CardContent>
                <InputStatusMessage v-if="page.props.flash?.success" :message="page.props.flash.success"
                    variant="success" class="mb-3" />

                <Link class="w-full" :href="route('verification.send')" method="post">
                <Button type="submit" class="w-full">
                    Resend Verification Email
                </Button>
                </Link>

                <Link class="block w-full mt-4" :href="route('logout')" method="post">
                <Button class="w-full" variant="secondary">Log Out</Button>
                </Link>
            </CardContent>
        </Card>
    </GuestLayout>
</template>
