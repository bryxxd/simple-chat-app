<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Settings from "@/Layouts/settings/Layout.vue";
import ProfilePicture from "@/Components/ProfilePicture.vue";
import { Input, InputStatusMessage } from "@/Components/ui/customInput";
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import Divider from "@/Components/Divider.vue";
import { usePage, useForm, Head } from '@inertiajs/vue3';
import { useFlashMessages } from "@/composables/useFlashMessage";
import {
    BreadcrumbItem,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { computed } from 'vue';

const page = usePage();

const user = computed(() => page.props.auth.user)

const form = useForm({
    first_name: user.value.first_name,
    last_name: user.value.last_name,
    email: user.value.email
});

const submit = () => {
    form.patch(route('settings.profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            useFlashMessages('Profile update');
        },
    })
};

</script>
<template>

    <Head title="Profile" />
    <AuthLayout>
        <template v-slot:currentPage>
            <BreadcrumbSeparator/>
            <BreadcrumbItem>
                <BreadcrumbPage>Profile</BreadcrumbPage>
            </BreadcrumbItem>
        </template>
        <Settings>
            <div>
                <h3 class="text-lg font-medium"> Profile </h3>
                <p class="text-sm text-muted-foreground">This is how others will see you on the site.</p>
            </div>
            <Divider />
            <form @submit.prevent="submit" class="space-y-8">
                <ProfilePicture :user="user" />
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <Label for="first_name" class="block text-sm font-medium">First Name</Label>
                        <Input v-model="form.first_name" id="first_name" />
                        <InputStatusMessage v-if="form.errors.first_name" class="pt-2"
                            :message="form.errors.first_name" />
                    </div>
                    <div>
                        <Label for="last_name" class="block text-sm font-medium">Last Name</Label>
                        <Input v-model="form.last_name" id="last_name" />
                        <InputStatusMessage v-if="form.errors.last_name" class="pt-2"
                            :message="form.errors.last_name" />
                    </div>
                </div>

                <div>
                    <Label for="email" class="block text-sm font-medium">Email</Label>
                    <Input v-model="form.email" id="email" />
                    <InputStatusMessage v-if="form.errors.email" class="pt-2" :message="form.errors.email" />
                </div>

                <div>
                    <Button type="submit" :disabled="form.processing">Update Profile</Button>
                </div>
            </form>
        </Settings>
    </AuthLayout>
</template>
