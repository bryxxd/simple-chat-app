<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Settings from "@/Layouts/settings/Layout.vue";
import { Avatar, AvatarImage } from "@/Components/ui/avatar";
import { Pencil } from "lucide-vue-next";
import { Input, InputError } from "@/Components/ui/input";
import { Popover, PopoverContent, PopoverTrigger } from '@/Components/ui/popover';
import { Button } from '@/Components/ui/button';
import { Label } from '@/Components/ui/label';
import Divider from "@/Components/Divider.vue";
import { usePage, useForm } from '@inertiajs/vue3';
import { useFlashMessages } from "@/composables/useFlashMessage";   
import { computed } from 'vue';

const page = usePage();

const user = computed(() => page.props.auth.user)

const form = useForm({
    first_name: user.value.first_name,
    last_name: user.value.last_name,
    username: user.value.username,
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
    <AuthLayout>
        <Settings>
            <div>
                <h3 class="text-lg font-medium"> Profile </h3>
                <p class="text-sm text-muted-foreground"> This is how others will see you on the site. </p>
            </div>
            <Divider/>
            <form @submit.prevent="submit" class="space-y-8">
                <div class="relative">
                    <Label for="profile-picture" class="block text-sm font-medium">Profile Picture</Label>
                    <Avatar class="w-40 h-40 mt-2">
                        <AvatarImage :src="user.avatar" />
                    </Avatar>
                    <Popover>
                        <PopoverTrigger class="absolute bottom-0 left-0" as-child>
                            <Button class="p-2" variant="outline">
                                <Pencil />
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0">
                            <div
                                class="hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer py-2 px-4">
                                <Label for="profile-picture" class="block w-full cursor-pointer">Upload a photo</Label>
                                <Input type="file" id="profile-picture" name="profile-picture"
                                    class="opacity-0 absolute cursor-pointer hidden" accept="image/*" />
                            </div>
                        </PopoverContent>
                    </Popover>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <Label for="first_name" class="block text-sm font-medium">First Name</Label>
                        <Input v-model="form.first_name" id="first_name" />
                        <InputError v-if="form.errors.first_name" class="pt-2" :message="form.errors.first_name" />
                    </div>
                    <div>
                        <Label for="last_name" class="block text-sm font-medium">Last Name</Label>
                        <Input v-model="form.last_name" id="last_name" />
                        <InputError v-if="form.errors.last_name" class="pt-2" :message="form.errors.last_name" />
                    </div>
                </div>

                <div>
                    <Label for="username" class="block text-sm font-medium">Username</Label>
                    <Input v-model="form.username" id="username" />
                    <InputError v-if="form.errors.username" class="pt-2" :message="form.errors.username" />
                </div>

                <div>
                    <Label for="email" class="block text-sm font-medium">Email</Label>
                    <Input v-model="form.email" id="email" />
                    <InputError v-if="form.errors.email" class="pt-2" :message="form.errors.email" />
                </div>

                <div>
                    <Button type="submit" :disabled="form.processing">Update Profile</Button>
                </div>
            </form>
        </Settings>

    </AuthLayout>
</template>
