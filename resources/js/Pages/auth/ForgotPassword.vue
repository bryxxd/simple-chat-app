<script>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Head,
        GuestLayout,
        Button,
        Card,
        CardContent,
        CardDescription,
        CardHeader,
        CardTitle,
        Input,
        Label,
        Link
    }
}
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
                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" required/>
                    </div>
                    <Button type="submit" class="w-full">Send</Button>
                </div>
                <div class="mt-4 text-center text-sm">
                    <Link href="/" class="underline">Back to Login</Link>
                </div>
            </CardContent>
        </Card>
    </GuestLayout>

</template>
