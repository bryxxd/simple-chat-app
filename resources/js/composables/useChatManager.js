import { ref, computed, shallowRef, onMounted, watchEffect } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";

export function useChatManager() {
    // Data / Reactive state
    const selectedUserId = ref(null);
    const onlineUsers = ref([]);
    const chatUsers = shallowRef([]);
    const currentChatMessages = shallowRef({ messages: [] });

    // Add new message to current chat if it belongs to the active conversation
    function addMessageToChat(from_user_id, to_user_id, content) {
        const isMessageForCurrentChat =
            (from_user_id === usePage().props.auth.user.id &&
                to_user_id === selectedUserId.value) ||
            (from_user_id === selectedUserId.value &&
                to_user_id === usePage().props.auth.user.id);

        // Ensure messages array exists
        if (!currentChatMessages.value.messages) {
            currentChatMessages.value = { messages: [] };
        }

        if (isMessageForCurrentChat) {
            // Create a new object reference for shallowRef reactivity
            currentChatMessages.value = {
                ...currentChatMessages.value,
                messages: [
                    ...currentChatMessages.value.messages,
                    {
                        content: content,
                        from_user_id: from_user_id,
                        to_user_id: to_user_id,
                    }
                ]
            };
        }
    }

    // Update recent chats list by moving user to top with latest message
    function updateRecentChats(newMsg) {
        const userIndex = chatUsers.value.findIndex(
            ({ id }) => id === newMsg.targetUserId,
        );

        if (userIndex !== -1) {
            const [user] = chatUsers.value.splice(userIndex, 1);
            // Preserve all existing properties and update specific ones
            const updatedUser = {
                ...user,
                content: newMsg.content,
                created_at: new Date().toISOString(),
            };
            chatUsers.value.unshift(updatedUser);
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
                chatUsers.value.unshift(newUser);
            }
        }
    }

    function updateSelectedUser(userID) {
        selectedUserId.value = userID;
    }

    // Computed properties
    const users = computed(() => {
        return usePage().props.users || [];
    });

    // Computed property to get the selected user's details
    const selectedUserDetails = computed(() => {
        if (!selectedUserId.value) {
            return null;
        }

        // First try interactedUsers
        if (chatUsers.value && chatUsers.value.length > 0) {
            const foundUser = chatUsers.value.find(
                (user) => user.id === selectedUserId.value,
            );
            if (foundUser) {
                return foundUser;
            }
        }

        // Then try all users
        if (users.value && users.value.length > 0) {
            const foundUser = users.value.find(
                (user) => user.id === selectedUserId.value,
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
            chatUsers.value = usePage().props.interactedUsers;
        }
        // Set the first interacted user as activeChat if none is set
        if (!selectedUserId.value && usePage().props.interactedUsers.length !== 0) {
            selectedUserId.value = usePage().props.interactedUsers[0].id;
        }
        // Listen for incoming messages
        window.Echo.private("new-messages." + usePage().props.auth.user.id).listen(
            "NewMessageEvent",
            (e) => {
                addMessageToChat(e.from_user_id, e.to_user_id, e.content);

                // Always update the user list, regardless of active chat
                const targetUserId =
                    e.from_user_id === usePage().props.auth.user.id
                        ? e.to_user_id
                        : e.from_user_id;
                updateRecentChats({
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
        if (!selectedUserId.value) {
            currentChatMessages.value = { messages: [] };
            return;
        }
        try {
            const res = await axios.get(`/api/chat-room/${selectedUserId.value}`);
            // Ensure we have a valid messages array
            currentChatMessages.value = {
                ...res.data,
                messages: Array.isArray(res.data.messages) ? res.data.messages : []
            };
        } catch (error) {
            console.error("Error loading chat messages:", error);
            // Initialize with empty messages array on error
            currentChatMessages.value = { messages: [] };
        }
    });

    return {
        // Data / Reactive state
        selectedUserId,
        onlineUsers,
        chatUsers,
        currentChatMessages,
        // Methods
        addMessageToChat,
        updateRecentChats,
        updateSelectedUser,
        // Computed properties
        users,
        selectedUserDetails,
        isOnline,
    }
}