<script>
import AppSidebar from "@/Components/AppSidebar.vue";
import ChatBox from "@/Components/ChatBox.vue";
import NavUser from "@/Components/NavUser.vue";
import { Room } from "@/Components/ui/room/";
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/Components/ui/sidebar";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { usePage } from "@inertiajs/vue3";
export default {
    name: "Dashboard",
    components: {
        AppSidebar,
        Breadcrumb,
        BreadcrumbItem,
        BreadcrumbLink,
        BreadcrumbList,
        BreadcrumbPage,
        BreadcrumbSeparator,
        ChatBox,
        SidebarInset,
        SidebarProvider,
        SidebarTrigger,
        NavUser,
        Room
    },
    data() {
        return {
            user: {
                name: "shadcn",
                email: "m@example.com",
                avatar: "/images/shadcn.jpg",
            },
            activeChat: null,
        };
    },
    methods: {
        handleActiveChat(chatID) {
            this.activeChat = chatID;
        },
    },
    computed: {
        updateActiveChat() {
            return this.activeChat ? this.interactedUsers.find(user => user.id === this.activeChat) : this.interactedUsers[0];
        },
        interactedUsers() {
            return usePage().props.users 
        }
    },
    mounted() {
        this.activeChat = this.interactedUsers.length > 0 ? this.interactedUsers[0].id : null;
    },
    provide() {
        return {
            interactedUsers: this.interactedUsers
        };
    },
};
</script>

<template>
    <SidebarProvider :style="{ '--sidebar-width': '350px' }">
        <AppSidebar @set-active-chat="handleActiveChat" />
        <SidebarInset>
            <header class="sticky top-0 flex shrink-0 items-center justify-between border-b bg-background p-2 md:p-4 z-50">
                <div class="flex items-center">
                    <SidebarTrigger class="-ml-1" />
                </div>
                <NavUser :user="user" class="w-[3rem] md:w-[2rem]" />
            </header>
            <div>
                <slot>
                    <ChatBox :user="updateActiveChat"/>
                </slot>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
