<script>
import AppSidebar from "@/Components/AppSidebar.vue";
import ChatBox from "@/Components/ChatBox.vue";
import NavUser from "@/Components/NavUser.vue";
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/Components/ui/sidebar";
import { usePage, Head, router } from "@inertiajs/vue3";
import axios from "axios";

export default {
    name: "AuthLayout",
    components: {
        Head,
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
            chat_data: {},
            isLoading: false,
            onlineUsers: []
        };
    },
    methods: {
        async fetchChatData() {
            if (!this.activeChat) return;
            try {
                this.isLoading = true;
                const res = await axios.get(`/api/chat-room/${this.activeChat}`);
                this.chat_data = res.data;
            } catch (error) {
                console.log('Error fetching chat room:', error);
            } finally {
                this.isLoading = false;
            }
        },
        handleFilterChat(userID) {
            this.activeChat = userID;
            this.fetchChatData();
        }
    },
    computed: {
        interactedUsers() {
            return usePage().props.users || [];
        },
        activeUser() {
            return this.activeChat ?
                this.interactedUsers.find(user => user.id === this.activeChat)
                : this.interactedUsers[0];
        },
    },
    mounted() {
        if (this.interactedUsers?.length > 0) {
            this.activeChat = this.interactedUsers[0].id;
            this.fetchChatData();
        }

        window.Echo.join('online-users')
            .here((users) => {
                // Handle initial list of online users
                users.forEach(e => {
                    this.onlineUsers.push(e.id)
                });
            })
            .joining((user) => {
                // Handle when a new user comes online
                this.onlineUsers.push(user.id)
            })
            .leaving((user) => {
                // Handle when a user goes offline
                this.onlineUsers.pop(user.id);

                (async function updateLastActive() {
                    try {
                        await axios.post(`/api/update-last-active/${user.id}`);
                    } catch (error) {
                        console.log('Error updating last active:', error);
                    }
                })();
            })
            .error((error) => {
                console.error('Error in presence channel:', error);
            });
    },
    provide() {
        return {
            interactedUsers: this.interactedUsers,
            isOnline: (activeUser) => {
                return this.onlineUsers.find(e => e == activeUser);
            }
        };
    },
};
</script>

<template>

    <Head title="Chat" />
    <SidebarProvider :style="{ '--sidebar-width': '350px' }">
        <AppSidebar @set-active-chat="handleFilterChat" />
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
                    <ChatBox :isLoading="isLoading" :chats="chat_data" :activeUser="activeUser" />
                </slot>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
