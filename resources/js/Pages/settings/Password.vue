<script>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import Settings from "@/Layouts/settings/Layout.vue";
import { Pencil } from "lucide-vue-next";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Head } from '@inertiajs/vue3';

export default {
    name: "Password",
    components: {
        Head,
        Button,
        Input,
        Popover,
        PopoverContent,
        PopoverTrigger,
        Settings,
        AuthLayout,
        Pencil
    },
    data() {
        return {
            dummyimage: "/images/shadcn.jpg",
        }
    },
};
</script>
<template>
    <Head title="Password" />
    <AuthLayout>
        <Settings>
            <div>
                <h3 class="text-lg font-medium"> Password </h3>
                <p class="text-sm text-muted-foreground"> This is how others will see you on the site. </p>
            </div>
            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full"></div>
            <form class="space-y-8">
                <div>
                    <label for="current_password" class="block text-sm font-medium">Current Password</label>
                    <input type="password" id="current_password" name="current_password"
                        class="mt-1 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Current Password" />
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium">New Password</label>
                    <input type="password" id="new_password" name="new_password"
                        class="mt-1 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="New Password" />
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="mt-1 flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="Confirm Password" />
                </div>

                <div>
                    <Button type="submit">Update Password</Button>
                </div>
            </form>
        </Settings>

    </AuthLayout>
</template>
