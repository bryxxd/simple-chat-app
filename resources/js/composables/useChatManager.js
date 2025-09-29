import { useLoadMessages } from "./useLoadMessages";
import { ref, computed, onMounted, onUnmounted, shallowRef, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";

// Singleton state - shared across all components
let chatManagerInstance = null;

export function useChatManager() {
    // Return existing instance if it exists
    if (chatManagerInstance) {
        return chatManagerInstance;
    }

    // Get the reactive references from useLoadMessages
    const { loadMessages, messagesData, totalMessages, loadError, hasMoreMessages } = useLoadMessages();

    // Data / Reactive state
    const selectedUserId = ref(null);
    const onlineUsers = ref([]);
    const chatUsers = shallowRef([]);

    // Add new message to current chat if it belongs to the active conversation
    function addMessageToChat(e) {
        const isMessageForCurrentChat =
            (e.from_user_id === usePage().props.auth.user.id &&
                e.to_user_id === selectedUserId.value) ||
            (e.from_user_id === selectedUserId.value &&
                e.to_user_id === usePage().props.auth.user.id);

        // Ensure messages array exists
        if (!messagesData.value.messages) {
            messagesData.value = { messages: [] };
        }

        if (isMessageForCurrentChat) {
            const newMessage = {
                id: e.id,
                from_user_id: e.from_user_id,
                to_user_id: e.to_user_id,
                content: e.content,
                created_at: e.created_at,
            };
            
            // Insert in correct position to maintain sort order
            const messages = [...messagesData.value.messages];
            const insertIndex = messages.findIndex(msg => msg.id > e.id);
            
            if (insertIndex === -1) {
                messages.push(newMessage);
            } else {
                messages.splice(insertIndex, 0, newMessage);
            }
            
            // Create a new object reference for shallowRef reactivity
            messagesData.value = {
                ...messagesData.value,
                messages
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
        if (!userID || selectedUserId.value === userID) return;
        selectedUserId.value = userID;        
        console.log('Selected user updated to:', selectedUserId.value);
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
            const userId =
                typeof userOrId === "object" ? userOrId.id : userOrId;
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
        if (
            !selectedUserId.value &&
            usePage().props.interactedUsers.length !== 0
        ) {
            selectedUserId.value = usePage().props.interactedUsers[0].id;
        }
        
        // Listen for incoming messages
        window.Echo.private(
            "new-messages." + usePage().props.auth.user.id,
        ).listen("NewMessageEvent", (e) => {
            console.log("Received new message event:", e);
            addMessageToChat(e);

            // Always update the user list, regardless of active chat
            const targetUserId =
                e.from_user_id === usePage().props.auth.user.id
                    ? e.to_user_id
                    : e.from_user_id;
            updateRecentChats({
                targetUserId: targetUserId,
                content: e.content,
            });
        });
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

    // Load chat messages whenever activeChat changes
    watch(
        selectedUserId,
        (newUserId) => {
            if (newUserId) {
                loadMessages(newUserId);
            }
        },
        { immediate: true },
    );

    // Create the instance object
    chatManagerInstance = {
        // Data / Reactive state
        selectedUserId,
        onlineUsers,
        chatUsers,
        messagesData,
        totalMessages,
        loadError,
        hasMoreMessages,
        // Methods
        addMessageToChat,
        updateRecentChats,
        updateSelectedUser,
        // Computed properties
        users,
        selectedUserDetails,
        isOnline,
    };

    return chatManagerInstance;
}
