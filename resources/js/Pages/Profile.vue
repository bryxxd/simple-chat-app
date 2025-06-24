<script>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { Pencil } from "lucide-vue-next";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

export default {
    name: "Profile",
    components: {
        Button,
        Input,
        Popover,
        PopoverContent,
        PopoverTrigger,
        AuthLayout,
        Pencil
    },
    data() {
        return {
            dummyimage: "./images/shadcn.jpg",
        }
    },
};
</script>
<template>
    <AuthLayout>
        <div class="space-y-6 p-5 md:p-10 pb-16 lg:max-w-2xl">
            <div class="space-y-0.5">
                <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
                <p class="text-muted-foreground">
                    Manage your account settings.
                </p>
            </div>
            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full my-6"></div>
            <div class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0">
                <div class="flex-1">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium">Profile</h3>
                            <p class="text-sm text-muted-foreground">
                                This is how others will see you on the site.
                            </p>
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
                                    <PopoverContent class="p-0 w-36">
                                        <div>
                                            <label for="profile-picture"
                                                class="hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer p-2 block">Upload
                                                a photo</label>
                                            <input type="file" id="profile-picture" name="profile-picture"
                                                class="opacity-0 absolute cursor-pointer" accept="image/*" />
                                        </div>
                                    </PopoverContent>
                                </Popover>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="name" class="block text-sm font-medium">First Name</label>
                                    <input type="text" id="name" name="name"
                                        class="mt-1 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                        placeholder="Your Name" />
                                </div>
                                <div>
                                    <label for="name" class="block text-sm font-medium">Last Name</label>
                                    <input type="text" id="name" name="name"
                                        class="mt-1 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                        placeholder="Your Name" />
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium">Email</label>
                                <input type="email" id="email" name="email"
                                    class="mt-1 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    placeholder="Your Email" />
                            </div>

                            <div>
                                <Button type="submit">Update Profile</Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
