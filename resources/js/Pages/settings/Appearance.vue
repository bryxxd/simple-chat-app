<script>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import AppearanceTabs from "@/Components/AppearanceTabs.vue";
import Settings from "@/Layouts/settings/Layout.vue";
import { Pencil } from "lucide-vue-next";
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

export default {
    name: "Appearance",
    components: {
        AppearanceTabs,
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
    <AuthLayout>
        <Settings>
            <div>
                <h3 class="text-lg font-medium"> Apperance </h3>
                <p class="text-sm text-muted-foreground"> This is how others will see you on the site. </p>
            </div>
            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full"></div>
            <AppearanceTabs />
        </Settings>

    </AuthLayout>
</template>
