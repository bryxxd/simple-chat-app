<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Settings from "@/Layouts/settings/Layout.vue";
import { Label } from '@/Components/ui/label';
import { Input, InputStatusMessage } from "@/Components/ui/customInput";
import { Button } from '@/components/ui/button';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {
    BreadcrumbItem,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { useFlashMessages } from "@/composables/useFlashMessage";

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
});
const submit = () => {
    form.post(route('settings.password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            useFlashMessages('Password update');
        },
    })
};
</script>
<template>

    <Head title="Password" />
    <AuthLayout>
        <template v-slot:currentPage>
            <BreadcrumbSeparator />
            <BreadcrumbItem>
                <BreadcrumbPage>Password</BreadcrumbPage>
            </BreadcrumbItem>
        </template>
        <Settings>
            <div>
                <h3 class="text-lg font-medium"> Password </h3>
                <p class="text-sm text-muted-foreground">Ensure your account is using a long, random password to
                    staysecure.</p>
            </div>
            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full"></div>
            <template v-if="user.provider === 'email'">
                <form class="space-y-8" @submit.prevent="submit">
                    <div>
                        <Label for="current_password" class="block text-sm font-medium">Current Password</Label>
                        <Input v-model="form.current_password" id="current_password" type="password" />
                        <InputStatusMessage v-if="form.errors.current_password" class="pt-2"
                            :message="form.errors.current_password" />
                    </div>

                    <div>
                        <Label for="password" class="block text-sm font-medium">New Password</Label>
                        <Input v-model="form.password" id="password" type="password" />
                        <InputStatusMessage v-if="form.errors.password" class="pt-2" :message="form.errors.password" />
                    </div>

                    <div>
                        <Label for="password_confirmation" class="block text-sm font-medium">Confirm Password</Label>
                        <Input v-model="form.password_confirmation" id="password_confirmation" type="password" />
                        <InputStatusMessage v-if="form.errors.password_confirmation" class="pt-2"
                            :message="form.errors.password_confirmation" />
                    </div>

                    <div>
                        <Button type="submit">Update Password</Button>
                    </div>
                </form>
            </template>
            <template v-else>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 border rounded flex items-center justify-center p-1.5 shadow-sm">
                        <img src="/images/google.png" alt="">
                    </div>
                    <span class="text-sm text-muted-foreground">This account uses Google Sign-In</span>
                </div>
            </template>
        </Settings>
    </AuthLayout>
</template>
