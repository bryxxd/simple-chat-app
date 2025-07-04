<script>
import AppSidebar from "@/components/AppSidebar.vue";
import ChatBox from "@/Components/ChatBox.vue";
import NavUser from "@/components/NavUser.vue";
import { Room } from "@/components/ui/room/";
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/components/ui/sidebar";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";

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
        Room,
    },
    data() {
        return {
            user: {
                name: "shadcn",
                email: "m@example.com",
                avatar: "./images/shadcn.jpg",
            },
            message: '',
            chats: [
                {
                    id: 1,
                    participant: {
                        id: 2,
                        name: "Sarah Johnson",
                        email: "sarah.johnson@example.com",
                        avatar: "./images/shadcn.jpg",
                        isOnline: true
                    },
                    lastMessage: {
                        content: "Perfect! See you at the coffee shop at 3 PM ☕",
                        timestamp: "2024-06-28T14:25:00Z",
                        senderId: 2
                    },
                    unreadCount: 2,
                    messages: [
                        {
                            id: 1,
                            senderId: 1,
                            content: "Hey Sarah! Are you free for coffee this afternoon?",
                            timestamp: "2024-06-28T14:20:00Z",
                            type: "text"
                        },
                        {
                            id: 2,
                            senderId: 2,
                            content: "Yes! I'd love to catch up. What time works for you?",
                            timestamp: "2024-06-28T14:22:00Z",
                            type: "text"
                        },
                        {
                            id: 3,
                            senderId: 1,
                            content: "How about 3 PM at our usual spot?",
                            timestamp: "2024-06-28T14:23:00Z",
                            type: "text"
                        },
                        {
                            id: 4,
                            senderId: 2,
                            content: "Perfect! See you at the coffee shop at 3 PM ☕",
                            timestamp: "2024-06-28T14:25:00Z",
                            type: "text"
                        }
                    ]
                },
                {
                    id: 2,
                    participant: {
                        id: 3,
                        name: "Mike Chen",
                        email: "mike.chen@example.com",
                        avatar: "./images/shadcn.jpg",
                        isOnline: false
                    },
                    lastMessage: {
                        content: "Thanks for sharing! I'll review it tonight.",
                        timestamp: "2024-06-28T11:45:00Z",
                        senderId: 3
                    },
                    unreadCount: 0,
                    messages: [
                        {
                            id: 5,
                            senderId: 1,
                            content: "Hey Mike, I finished the project proposal. Want to take a look?",
                            timestamp: "2024-06-28T11:30:00Z",
                            type: "text"
                        },
                        {
                            id: 6,
                            senderId: 1,
                            content: "I've attached the PDF here",
                            timestamp: "2024-06-28T11:31:00Z",
                            type: "file"
                        },
                        {
                            id: 7,
                            senderId: 3,
                            content: "Awesome! Let me download it now.",
                            timestamp: "2024-06-28T11:40:00Z",
                            type: "text"
                        },
                        {
                            id: 8,
                            senderId: 3,
                            content: "Thanks for sharing! I'll review it tonight.",
                            timestamp: "2024-06-28T11:45:00Z",
                            type: "text"
                        }
                    ]
                },
                {
                    id: 3,
                    participant: {
                        id: 4,
                        name: "Emma Wilson",
                        email: "emma.wilson@example.com",
                        avatar: "./images/shadcn.jpg",
                        isOnline: true
                    },
                    lastMessage: {
                        content: "Can't wait! It's going to be amazing! 🎉",
                        timestamp: "2024-06-28T16:10:00Z",
                        senderId: 4
                    },
                    unreadCount: 1,
                    messages: [
                        {
                            id: 9,
                            senderId: 4,
                            content: "OMG! Did you see the announcement about the concert?",
                            timestamp: "2024-06-28T16:05:00Z",
                            type: "text"
                        },
                        {
                            id: 10,
                            senderId: 1,
                            content: "Yes! I already got my tickets. Are you going?",
                            timestamp: "2024-06-28T16:07:00Z",
                            type: "text"
                        },
                        {
                            id: 11,
                            senderId: 4,
                            content: "Definitely! Just bought mine too.",
                            timestamp: "2024-06-28T16:08:00Z",
                            type: "text"
                        },
                        {
                            id: 12,
                            senderId: 4,
                            content: "Can't wait! It's going to be amazing! 🎉",
                            timestamp: "2024-06-28T16:10:00Z",
                            type: "text"
                        }
                    ]
                },
                {
                    id: 4,
                    participant: {
                        id: 5,
                        name: "David Rodriguez",
                        email: "david.rodriguez@example.com",
                        avatar:"./images/shadcn.jpg",
                        isOnline: false
                    },
                    lastMessage: {
                        content: "Sounds good! Let me know when you're ready.",
                        timestamp: "2024-06-28T09:15:00Z",
                        senderId: 5
                    },
                    unreadCount: 0,
                    messages: [
                        {
                            id: 13,
                            senderId: 1,
                            content: "Morning David! Ready for our gym session today?",
                            timestamp: "2024-06-28T09:00:00Z",
                            type: "text"
                        },
                        {
                            id: 14,
                            senderId: 5,
                            content: "Hey! Yes, but I'm running a bit late.",
                            timestamp: "2024-06-28T09:10:00Z",
                            type: "text"
                        },
                        {
                            id: 15,
                            senderId: 1,
                            content: "No worries, take your time. I'll warm up first.",
                            timestamp: "2024-06-28T09:12:00Z",
                            type: "text"
                        },
                        {
                            id: 16,
                            senderId: 5,
                            content: "Sounds good! Let me know when you're ready.",
                            timestamp: "2024-06-28T09:15:00Z",
                            type: "text"
                        }
                    ]
                },
                {
                    id: 5,
                    participant: {
                        id: 6,
                        name: "Lisa Thompson",
                        email: "lisa.thompson@example.com",
                        avatar: "./images/shadcn.jpg",
                        isOnline: true
                    },
                    lastMessage: {
                        content: "That recipe sounds delicious! Please share it 🍝",
                        timestamp: "2024-06-28T18:30:00Z",
                        senderId: 6
                    },
                    unreadCount: 3,
                    messages: [
                        {
                            id: 17,
                            senderId: 6,
                            content: "Hi! How was your cooking class today?",
                            timestamp: "2024-06-28T18:20:00Z",
                            type: "text"
                        },
                        {
                            id: 18,
                            senderId: 1,
                            content: "It was fantastic! We learned to make authentic Italian pasta.",
                            timestamp: "2024-06-28T18:25:00Z",
                            type: "text"
                        },
                        {
                            id: 19,
                            senderId: 1,
                            content: "The chef shared some amazing secret techniques!",
                            timestamp: "2024-06-28T18:27:00Z",
                            type: "text"
                        },
                        {
                            id: 20,
                            senderId: 6,
                            content: "That recipe sounds delicious! Please share it 🍝",
                            timestamp: "2024-06-28T18:30:00Z",
                            type: "text"
                        }
                    ]
                }
            ],
            activeChat: null,
        };
    },
    methods: {
        handleActiveChat(chatID) {
            this.activeChat = chatID;
        },
        handleNewMessage(activeChatID) {
            const currentChat = this.chats.find((chat) => chat.id === activeChatID);
            currentChat.messages.push({
                id : currentChat.messages.length + 1,
                senderId : 1,
                content: this.message,
                timestamp: new Date().toISOString(),
                type: "text",
            });
            this.message = '';
        }
    },
    computed: {
        updateActiveChat() {
            return this.activeChat
                ? this.chats.find((chat) => chat.id === this.activeChat)
                : this.chats[0];
        },
    },
    mounted() {
        this.activeChat = this.chats[0]?.id || null;
    },
    provide() {
        return {
            user: {
                name: "shadcn",
                email: "m@example.com",
                avatar: "./images/shadcn.jpg",
            },
            chats: this.chats,
        };
    },
};
</script>

<template>
    <SidebarProvider :style="{ '--sidebar-width': '350px' }">
        <AppSidebar @set-active-chat="handleActiveChat" />
        <SidebarInset>
            <header class="sticky top-0 flex shrink-0 items-center justify-between border-b bg-background p-2 md:p-4">
                <div class="flex items-center">
                    <SidebarTrigger class="-ml-1" />
                    <!-- <Separator orientation="vertical" class="mr-2 h-4" />
                    <Breadcrumb>
                        <BreadcrumbList>
                            <BreadcrumbItem class="hidden md:block">
                                <BreadcrumbLink href="#">
                                    All Inboxes
                                </BreadcrumbLink>
                            </BreadcrumbItem>
                            <BreadcrumbSeparator class="hidden md:block" />
                            <BreadcrumbItem>
                                <BreadcrumbPage>Test</BreadcrumbPage>
                            </BreadcrumbItem>
                        </BreadcrumbList>
                    </Breadcrumb> -->
                </div>
                <NavUser :user="user" class="w-[3rem] md:w-[2rem]" />
            </header>
            <div>
                <slot>
                    <ChatBox :activeChat="updateActiveChat" @send-message="handleNewMessage" v-model="message" />
                </slot>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
