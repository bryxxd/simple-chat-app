<script setup>
import AppSidebar from "@/Components/AppSidebar.vue";
import ChatBox from "@/Components/ChatBox.vue";
import NavUser from "@/Components/NavUser.vue";
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/Components/ui/sidebar";
import { usePage, Head } from "@inertiajs/vue3";
import axios from "axios";
import { reactive, computed, onMounted, provide } from "vue";

// Data / Reactive state
const data = reactive({
    activeChat: null,
    chat_data: {},
    isLoading: false,
    onlineUsers: []
});

// Methods 
async function fetchChatData() {
    if (!data.activeChat) return;
    try {
        data.isLoading = true;
        const res = await axios.get(`/api/chat-room/${data.activeChat}`);
        data.chat_data = res.data;
    } catch (error) {
        console.log('Error fetching chat room:', error);
    } finally {
        data.isLoading = false;
    }
};

function handleFilterChat(userID) {
    data.activeChat = userID;
    fetchChatData();
}

// Computed properties
const interactedUsers = computed(() => {
    return usePage().props.users || [];
});

const activeUser = computed(() => {
    return data.activeChat ?
        interactedUsers.value.find(user => user.id === data.activeChat)
        : interactedUsers.value[0];
});

// Lifecycle hooks
onMounted(() => {
    if (interactedUsers?.length > 0) {
        data.activeChat = interactedUsers[0].id;
        fetchChatData();
    }

    window.Echo.join('online-users')
        .here((users) => {
            // Handle initial list of online users
            users.forEach(e => {
                data.onlineUsers.push(e.id)
            });
        })
        .joining((user) => {
            // Handle when a new user comes online
            data.onlineUsers.push(user.id)
        })
        .leaving((user) => {
            // Handle when a user goes offline
            (async function updateLastActive() {
                try {
                    await axios.post(`/api/update-last-active/${user.id}`);
                } catch (error) {
                    console.log('Error updating last active:', error);
                }
            })();

            setTimeout(() => {
                data.onlineUsers.pop(user.id);
            }, 60000);

        })
        .error((error) => {
            console.error('Error in presence channel:', error);
        });
})

// Provide
provide('interactedUsers', interactedUsers);
provide('isOnline', (activeUser) => data.onlineUsers.includes(activeUser.id));
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
                    <ChatBox :isLoading="data.isLoading" :chats="data.chat_data" :activeUser="activeUser" />
                </slot>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
