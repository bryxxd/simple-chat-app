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
import { reactive, computed, onMounted, provide, watchEffect } from "vue";

// Data / Reactive state
const data = reactive({
    activeChat: null,
    messagesData: { messages: [] },
    onlineUsers: [],
    interactedUsers: [],
});

// Functions
function addMessage(from_user_id, to_user_id, content) {
    const isMessageForCurrentChat =
        (from_user_id === usePage().props.auth.user.id && to_user_id === data.activeChat) ||
        (from_user_id === data.activeChat && to_user_id === usePage().props.auth.user.id);

    if (isMessageForCurrentChat && data.messagesData.messages) {
        data.messagesData.messages.push({
            content: content,
            from_user_id: from_user_id,
            to_user_id: to_user_id
        });
        return true; // Message was added
    }
    return false; // Message was not for current chat
}

function handleIncomingMessage(from_user_id, to_user_id, content) {
    const messageAdded = addMessage(from_user_id, to_user_id, content);

    if (messageAdded) {
        const targetUserId = from_user_id === usePage().props.auth.user.id ? to_user_id : from_user_id;
        moveUserToTop({ targetUserId, content });
    }
}

function moveUserToTop(newMsg) {
    const userIndex = data.interactedUsers.findIndex(({ id }) => id === newMsg.targetUserId);

    if (userIndex !== -1) {
        const [user] = data.interactedUsers.splice(userIndex, 1);
        // Preserve all existing properties and update specific ones
        const updatedUser = {
            ...user,
            content: newMsg.content,
            created_at: new Date().toISOString()
        };
        data.interactedUsers.unshift(updatedUser);
    } else {
        // If user not found in interactedUsers, find them in all users and add them
        const userFromAllUsers = users.value.find(user => user.id === newMsg.targetUserId);
        // Add the content property when adding new user
        if (userFromAllUsers) {
            const newUser = {
                ...userFromAllUsers,
                content: newMsg.content || "",
                created_at: new Date().toISOString()
            };
            data.interactedUsers.unshift(newUser);
        }
    }
}

function handleFilterChat(userID) {
    data.activeChat = userID;
}

// Computed properties
const users = computed(() => {
    return usePage().props.users || [];
});

const activeUser = computed(() => {
    if (!data.activeChat) {
        return null;
    }

    // First try interactedUsers
    if (data.interactedUsers && data.interactedUsers.length > 0) {
        const foundUser = data.interactedUsers.find(user => user.id === data.activeChat);
        if (foundUser) {
            return foundUser;
        }
    }

    // Then try all users
    if (users.value && users.value.length > 0) {
        const foundUser = users.value.find(user => user.id === data.activeChat);
        if (foundUser) {
            return foundUser;
        }
    }

    return null;
});

const isOnline = computed(() => {
    return (userOrId) => {
        const userId = typeof userOrId === 'object' ? userOrId.id : userOrId;
        return data.onlineUsers.includes(userId);
    };
});


// Lifecycle hooks
onMounted(() => {
    if (usePage().props.interactedUsers.length !== 0) {
        data.interactedUsers.push(...usePage().props.interactedUsers)

    }

    if (!data.activeChat && usePage().props.interactedUsers.length !== 0) {
        data.activeChat = usePage().props.interactedUsers[0].id
    }

    window.Echo.private('new-messages.' + usePage().props.auth.user.id)
        .listen('NewMessageEvent', e => {
            handleIncomingMessage(e.from_user_id, e.to_user_id, e.content);
        });

    window.Echo.join('online-users')
        .here((users) => {
            // Handle initial list of online users
            data.onlineUsers = []; // Clear existing array
            users.forEach(e => {
                data.onlineUsers.push(e.id)
            });
        })
        .joining((user) => {
            // Handle when a new user comes online
            if (!data.onlineUsers.includes(user.id)) {
                data.onlineUsers.push(user.id);
            }
        })
        .leaving((user) => {
            // Handle when a user goes offline
            (async () => {
                try {
                    await axios.post(`/api/update-last-active/${user.id}`);
                } catch (error) {
                    console.log('Error updating last active:', error);
                }
            })();

            setTimeout(() => {
                const index = data.onlineUsers.indexOf(user.id);
                if (index > -1) {
                    data.onlineUsers.splice(index, 1);
                }
            }, 60000);

        })
        .error((error) => {
            console.error('Error in presence channel:', error);
        });
})

// watchEffect
watchEffect(async () => {
    if (!data.activeChat) return;
    
    try {
        const res = await axios.get(`/api/chat-room/${data.activeChat}`);
        data.messagesData = res.data;
    } catch (error) {
        console.error('Error loading chat messages:', error);
        // Initialize with empty messages array on error
        data.messagesData = { messages: [] };
    }
})

// Provide
provide('interactedUsers', data.interactedUsers);
provide('isOnline', isOnline);
provide('moveUserToTop', moveUserToTop)
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
                    <ChatBox :chats="data.messagesData" :activeUser="activeUser" />
                </slot>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
