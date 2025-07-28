<script>
import AppSidebar from "@/Components/AppSidebar.vue";
import ChatBox from "@/Components/ChatBox.vue";
import NavUser from "@/Components/NavUser.vue";
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/Components/ui/sidebar";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
export default {
    name: "AuthLayout",
    components: {
        AppSidebar,
        ChatBox,
        SidebarInset,
        SidebarProvider,
        SidebarTrigger,
        NavUser,
    },
    data() {
        return {
            activeChat: null,
            chats: [],
            to_user_avatar: '',
        };
    },
    methods: {
        handleActiveChat(chatID) {
            this.activeChat = chatID;
        },
        async fetchMessages() {
            if (!this.activeChat) return;
            try {
                const res = await axios.get(`/api/messages/${this.activeChat}`);
                this.chats = res.data.messages || [];
                this.to_user_avatar = res.data.to_user_avatar.avatar || '';
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }
    },
    computed: {
        activeUser() {
            return this.activeChat ? this.interactedUsers.find(user => user.id === this.activeChat) : this.interactedUsers[0];
        },
        interactedUsers() {
            return usePage().props.users || [];
        }
    },
    mounted() {
        if (this.interactedUsers?.length > 0) {
            this.activeChat = this.interactedUsers[0].id;
            this.fetchMessages();
        }
    },
    watch: {
        activeChat(newChatID) {
            this.fetchMessages();
        }
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
            <header
                class="sticky top-0 flex shrink-0 items-center justify-between border-b bg-background p-2 md:p-4 z-50">
                <div class="flex items-center">
                    <SidebarTrigger class="-ml-1" />
                </div>
                <NavUser class="w-[3rem] md:w-[2rem]" />
            </header>
            <div>
                <slot>
                    <ChatBox :user="activeUser" :chats="chats" :to_user_avatar="to_user_avatar" />
                </slot>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
