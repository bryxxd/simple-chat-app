<script>
import AppSidebar from '@/components/AppSidebar.vue';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Separator } from '@/components/ui/separator';
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import NavUser from '@/components/NavUser.vue';

export default {
    name: 'Dashboard',
    components: {
        AppSidebar,
        Breadcrumb,
        BreadcrumbItem,
        BreadcrumbLink,
        BreadcrumbList,
        BreadcrumbPage,
        BreadcrumbSeparator,
        Separator,
        SidebarInset,
        SidebarProvider,
        SidebarTrigger,
        NavUser
    },
    data() {
        return {
            user: {
                name: "shadcn",
                email: "m@example.com",
                avatar: "./images/shadcn.jpg",
            },
            isMobileSize: true,
        }
    },
};
</script>

<template>
    <SidebarProvider :style="{ '--sidebar-width': '350px', }">
        <AppSidebar />
        <SidebarInset>
            <header class="sticky top-0 flex shrink-0 items-center justify-between border-b bg-background p-4">
                <SidebarTrigger class="-ml-1" />
                <NavUser :user="user" class="w-[2rem] hidden md:block" />
            </header>
            <div class="flex flex-1 flex-col gap-4 p-4">
                <div v-for="index in 24" :key="index" class="aspect-video h-12 w-full rounded-lg bg-muted/50" />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
