<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Settings from "@/Layouts/settings/Layout.vue";
import { Pencil } from "lucide-vue-next";
import { Input } from "@/components/ui/input";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage()

const user = computed(() => page.props.auth.user)

const form = useForm({
    first_name: user.first_name
})


// Other reactive data
const dummyimage = ref("/images/shadcn.jpg");
</script>
<template>
    <AuthLayout>
        <Settings>
            <div>
                <h3 class="text-lg font-medium"> Profile </h3>
                <p class="text-sm text-muted-foreground"> This is how others will see you on the site. </p>
            </div>
            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full"></div>
            <form class="space-y-8">
                <div class="relative">
                    <label for="email" class="block text-sm font-medium">Profile Picture</label>
                    <img class="w-40 h-40 rounded-full mt-2" :src="dummyimage" alt="">
                    <Popover>
                        <PopoverTrigger class="absolute bottom-0 left-0" as-child>
                            <Button class="p-2" variant="outline">
                                <Pencil />Edit
                            </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0">
                            <div class="hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer py-1 px-2">
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
                        <Input id="first_name" />
                    </div>
                    <div>
                        <Label for="last_name" class="block text-sm font-medium">Last Name</Label>
                        <Input id="last_name" />
                    </div>
                </div>

                <div>
                     <Label for="username" class="block text-sm font-medium">Username</Label>
                    <Input id="username" />
                </div>

                <div>
                    <Label for="email" class="block text-sm font-medium">Email</Label>
                    <Input id="email" />
                </div>

                <div>
                    <Button type="submit">Update Profile</Button>
                </div>
            </form>
        </Settings>

    </AuthLayout>
</template>
