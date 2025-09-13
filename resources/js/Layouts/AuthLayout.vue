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
import { ref, computed, onMounted, provide, watchEffect } from "vue";
import EmptyChatBox from "@/Components/EmptyChatBox.vue";

// Data / Reactive state
const activeChat = ref(null);
const onlineUsers = ref([]);
const interactedUsers = ref([]);
const messagesData = ref({ messages: []});

// Handle incoming messages
function handleIncomingMessage(from_user_id, to_user_id, content) {
    const isMessageForCurrentChat =
        (from_user_id === usePage().props.auth.user.id &&
            to_user_id === activeChat.value) ||
        (from_user_id === activeChat.value &&
            to_user_id === usePage().props.auth.user.id);

    if (isMessageForCurrentChat && messagesData.value.messages) {
        messagesData.value.messages.push({
            content: content,
            from_user_id: from_user_id,
            to_user_id: to_user_id,
        });
    }
}

// Move user to top of interactedUsers list
function moveUserToTop(newMsg) {
    const userIndex = interactedUsers.value.findIndex(
        ({ id }) => id === newMsg.targetUserId,
    );

    if (userIndex !== -1) {
        const [user] = interactedUsers.value.splice(userIndex, 1);
        // Preserve all existing properties and update specific ones
        const updatedUser = {
            ...user,
            content: newMsg.content,
            created_at: new Date().toISOString(),
        };
        interactedUsers.value.unshift(updatedUser);
    } else {
        // If user not found in interactedUsers, find them in all users and add them
        const userFromAllUsers = users.value.find(
            (user) => user.id === newMsg.targetUserId,
        );
        // Add the content property when adding new user
        if (userFromAllUsers) {
            const newUser = {
                ...userFromAllUsers,
                content: newMsg.content || "",
                created_at: new Date().toISOString(),
            };
            interactedUsers.value.unshift(newUser);
        }
    }
}

function handleFilterChat(userID) {
    activeChat.value = userID;
}

// Computed properties
const users = computed(() => {
    return usePage().props.users || [];
});

// Computed property to get the active user object based on activeChat
const activeUser = computed(() => {
    if (!activeChat.value) {
        return null;
    }

    // First try interactedUsers
    if (interactedUsers.value && interactedUsers.value.length > 0) {
        const foundUser = interactedUsers.value.find(
            (user) => user.id === activeChat.value,
        );
        if (foundUser) {
            return foundUser;
        }
    }

    // Then try all users
    if (users.value && users.value.length > 0) {
        const foundUser = users.value.find(
            (user) => user.id === activeChat.value,
        );
        if (foundUser) {
            return foundUser;
        }
    }

    return null;
});

// Computed property to check if a user is online
const isOnline = computed(() => {
    return (userOrId) => {
        const userId = typeof userOrId === "object" ? userOrId.id : userOrId;
        return onlineUsers.value.includes(userId);
    };
});

// Lifecycle hooks
onMounted(() => {
    // Initialize interactedUsers from props if available
    if (usePage().props.interactedUsers.length !== 0) {
        interactedUsers.value.push(...usePage().props.interactedUsers);
    }
    // Set the first interacted user as activeChat if none is set
    if (!activeChat.value && usePage().props.interactedUsers.length !== 0) {
        activeChat.value = usePage().props.interactedUsers[0].id;
    }
    // Listen for incoming messages
    window.Echo.private("new-messages." + usePage().props.auth.user.id).listen(
        "NewMessageEvent",
        (e) => {
            handleIncomingMessage(e.from_user_id, e.to_user_id, e.content);

            // Always update the user list, regardless of active chat
            const targetUserId =
                e.from_user_id === usePage().props.auth.user.id
                    ? e.to_user_id
                    : e.from_user_id;
            moveUserToTop({
                targetUserId: targetUserId,
                content: e.content,
            });
        },
    );
    // Presence channel for online users
    window.Echo.join("online-users")
        .here((users) => {
            // Handle initial list of online users
            onlineUsers.value = []; // Clear existing array
            users.forEach((e) => {
                onlineUsers.value.push(e.id);
            });
        })
        .joining((user) => {
            // Handle when a new user comes online
            if (!onlineUsers.value.includes(user.id)) {
                onlineUsers.value.push(user.id);
            }
        })
        .leaving((user) => {
            // Handle when a user goes offline
            (async () => {
                try {
                    await axios.post(`/api/update-last-active/${user.id}`);
                } catch (error) {
                    console.log("Error updating last active:", error);
                }
            })();

            setTimeout(() => {
                const index = onlineUsers.value.indexOf(user.id);
                if (index > -1) {
                    onlineUsers.value.splice(index, 1);
                }
            }, 60000);
        })
        .error((error) => {
            console.error("Error in presence channel:", error);
        });
});

// watchEffect
// Load chat messages whenever activeChat changes
watchEffect(async () => {
    if (!activeChat.value) return;
    try {
        const res = await axios.get(`/api/chat-room/${activeChat.value}`);
        messagesData.value = res.data;
    } catch (error) {
        console.error("Error loading chat messages:", error);
        // Initialize with empty messages array on error
        messagesData.value = { messages: [] };
    }
});

// Provide
provide("interactedUsers", interactedUsers.value);
provide("isOnline", isOnline);
provide("moveUserToTop", moveUserToTop);
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
                    <template v-if="interactedUsers.value && interactedUsers.value.length > 0 || activeUser">
                        <ChatBox :chats="messagesData" :activeUser="activeUser" />
                    </template>
                    <template v-else>
                        <EmptyChatBox />
                    </template>
                </slot>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
