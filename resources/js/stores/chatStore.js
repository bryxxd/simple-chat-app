import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'

export const useChatStore = defineStore('chat', () => {
    // State
    const selectedUserId = ref(null)
    const chatUsers = ref([])
    const onlineUsers = ref([])
    const messagesData = ref({})
    const totalMessages = ref(0)
    const perPage = ref(10);
    const currentPage = ref(1);
    const loadError = ref(null)
    const hasMorePages = ref(false)

    // Cookie management
    const setCookieActiveRoom = (roomId) => {
        document.cookie = `activeRoom=${roomId}; path=/; max-age=86400`; // 1 day
    }

    const getCookieActiveRoom = () => {
        const match = document.cookie.match(new RegExp('(^| )activeRoom=([^;]+)'))
        return match ? match[2] : null
    }

    // Computed
    const users = computed(() => {
        return usePage().props.users || []
    })

    const selectedUserDetails = computed(() => {
        if (!selectedUserId.value) {
            return null
        }

        // First try interactedUsers
        if (chatUsers.value && chatUsers.value.length > 0) {
            const foundUser = chatUsers.value.find(
                (user) => user.id === selectedUserId.value,
            )
            if (foundUser) {
                return foundUser
            }
        }

        // Then try all users
        if (users.value && users.value.length > 0) {
            const foundUser = users.value.find(
                (user) => user.id === selectedUserId.value,
            )
            if (foundUser) {
                return foundUser
            }
        }

        return null
    })

    const isOnline = computed(() => {
        return (userOrId) => {
            const userId = typeof userOrId === "object" ? userOrId.id : userOrId
            return onlineUsers.value.includes(userId)
        }
    })

    // Actions
    const updateSelectedUser = (userId) => {
        if (selectedUserId.value === userId) return;

        selectedUserId.value = userId
        setCookieActiveRoom(userId)

        // Load messages immediately when user is selected
        if (userId) {
            onLoadMessages(userId)
        }
    }

    const onLoadMessages = async (userId) => {
        if (!userId) return

        try {
            loadError.value = null
            const res = await axios.get(`/api/chat-room/${userId}`)
            currentPage.value = 1;

            if (res.data) {
                const sortedMessages = res.data.messages.sort((a, b) => a.id - b.id)
                messagesData.value = {
                    ...res.data,
                    messages: sortedMessages
                }
                totalMessages.value = res.data.totalMessages;
                hasMorePages.value = res.data.hasMorePages;
            }
        } catch (error) {
            console.error('Error loading messages:', error);
            loadError.value = 'Failed to load messages'
        }
    }

    const onLoadMore = async () => {
        if (!selectedUserId.value || !hasMorePages.value) return

        try {
            hasMorePages.value = false; 
            const nextPage = currentPage.value + 1;
            const res = await axios.get(`/api/chat-room/${selectedUserId.value}`, {
                params: {
                    page: nextPage,
                    per_page: perPage.value,
                }
            })

            if (res.data.messages) {
                const sortedNewMessages = res.data.messages.sort((a, b) => a.id - b.id)
                messagesData.value.messages.unshift(...sortedNewMessages)
                hasMorePages.value = res.data.hasMorePages;
                currentPage.value = nextPage;
            }
        } catch (error) {
            console.error('Pinia: Error loading more messages:', error)
            loadError.value = 'Failed to load more messages'
        }
    }

    const addMessageToChat = (e) => {
        const isMessageForCurrentChat =
            (e.from_user_id === usePage().props.auth.user.id &&
                e.to_user_id === selectedUserId.value) ||
            (e.from_user_id === selectedUserId.value &&
                e.to_user_id === usePage().props.auth.user.id)

        if (isMessageForCurrentChat) {
            const newMessage = {
                id: e.id,
                from_user_id: e.from_user_id,
                to_user_id: e.to_user_id,
                content: e.content,
                created_at: e.created_at,
            }

            // Insert in correct position to maintain sort order
            const messages = [...messagesData.value.messages]
            const insertIndex = messages.findIndex(msg => msg.id > e.id)

            if (insertIndex === -1) {
                messages.push(newMessage)
            } else {
                messages.splice(insertIndex, 0, newMessage)
            }

            messagesData.value = {
                ...messagesData.value,
                messages
            }
        }
    }

    const updateRecentChats = (newMsg) => {
        const userIndex = chatUsers.value.findIndex(
            ({ id }) => id === newMsg.targetUserId,
        )

        if (userIndex !== -1) {
            const [user] = chatUsers.value.splice(userIndex, 1)
            // Preserve all existing properties and update specific ones
            const updatedUser = {
                ...user,
                content: newMsg.content,
                created_at: new Date().toISOString(),
            }
            chatUsers.value.unshift(updatedUser)
        } else {
            // If user not found in interactedUsers, find them in all users and add them
            const userFromAllUsers = users.value.find(
                (user) => user.id === newMsg.targetUserId,
            )
            // Add the content property when adding new user
            if (userFromAllUsers) {
                const newUser = {
                    ...userFromAllUsers,
                    content: newMsg.content || "",
                    created_at: new Date().toISOString(),
                }
                chatUsers.value.unshift(newUser)
            }
        }
    }

    const initializeFromProps = () => {
        const props = usePage().props

        // Initialize interactedUsers from props if available
        if (props.interactedUsers && props.interactedUsers.length !== 0) {
            chatUsers.value = props.interactedUsers

            // Check if we have a cookie value first
            const cookieRoomId = getCookieActiveRoom()
            if (cookieRoomId) {
                const roomId = isNaN(cookieRoomId) ? cookieRoomId : parseInt(cookieRoomId)
                const userExists = props.interactedUsers.some(user => user.id == roomId)

                if (userExists) {
                    updateSelectedUser(roomId)
                    return
                }
            }

            // Fallback to first user if no valid cookie
            updateSelectedUser(props.interactedUsers[0].id)
        }
    }

    const setupEchoListeners = () => {
        // Listen for incoming messages
        window.Echo.private(
            "new-messages." + usePage().props.auth.user.id,
        ).listen("NewMessageEvent", (e) => {
            addMessageToChat(e)

            // Always update the user list, regardless of active chat
            const targetUserId =
                e.from_user_id === usePage().props.auth.user.id
                    ? e.to_user_id
                    : e.from_user_id
            updateRecentChats({
                targetUserId: targetUserId,
                content: e.content,
            })
        })

        // Presence channel for online users
        window.Echo.join("online-users")
            .here((users) => {
                // Handle initial list of online users
                onlineUsers.value = [] // Clear existing array
                users.forEach((e) => {
                    onlineUsers.value.push(e.id)
                })
            })
            .joining((user) => {
                // Handle when a new user comes online
                if (!onlineUsers.value.includes(user.id)) {
                    onlineUsers.value.push(user.id)
                }
            })
            .leaving((user) => {
                // Handle when a user goes offline
                (async () => {
                    try {
                        await axios.post(`/api/update-last-active/${user.id}`)
                    } catch (error) {
                        console.log("Error updating last active:", error)
                    }
                })()

                setTimeout(() => {
                    const index = onlineUsers.value.indexOf(user.id)
                    if (index > -1) {
                        onlineUsers.value.splice(index, 1)
                    }
                }, 60000)
            })
            .error((error) => {
                console.error("Error in presence channel:", error)
            })
    }

    return {
        // State
        selectedUserId,
        chatUsers,
        onlineUsers,
        messagesData,
        totalMessages,
        perPage,
        currentPage,
        loadError,
        hasMorePages,

        // Computed
        users,
        selectedUserDetails,
        isOnline,

        // Actions
        updateSelectedUser,
        onLoadMessages,
        onLoadMore,
        addMessageToChat,
        updateRecentChats,
        initializeFromProps,
        setupEchoListeners
    }
})