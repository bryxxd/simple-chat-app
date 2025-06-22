<script>
import { computed } from 'vue'
import AppSidebar from '@/components/AppSidebar.vue';
import ChatBox from '@/pages/ChatBox.vue';
import { ArchiveX, File, Inbox, Send, Trash2 } from "lucide-vue-next";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Separator } from '@/components/ui/separator';
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from '@/components/ui/sidebar';
import NavUser from '@/components/NavUser.vue';

export default {
    name: 'Dashboard',
    components: {
        AppSidebar,
        Breadcrumb,
        BreadcrumbItem,
        BreadcrumbLink,
        BreadcrumbList,
        BreadcrumbPage,
        BreadcrumbSeparator,
        ChatBox,
        Separator,
        SidebarInset,
        SidebarProvider,
        SidebarTrigger,
        NavUser
    },
    data() {
        return {
            user: {
                name: "shadcn",
                email: "m@example.com",
                avatar: "./images/shadcn.jpg",
            },
            activeChat: null,
            mails: [
                { id: 1, name: "William Smith", email: "williamsmith@example.com", subject: "Meeting Tomorrow", date: "09:34 AM", teaser: "Hi team, just a reminder about our meeting tomorrow at 10 AM.\nPlease come prepared with your project updates." },
                { id: 2, name: "Alice Smith", email: "alicesmith@example.com", subject: "Re: Project Update", date: "Yesterday", teaser: "Thanks for the update. The progress looks great so far.\nLet's schedule a call to discuss the next steps." },
                { id: 3, name: "Bob Johnson", email: "bobjohnson@example.com", subject: "Weekend Plans", date: "2 days ago", teaser: "Hey everyone! I'm thinking of organizing a team outing this weekend.\nWould you be interested in a hiking trip or a beach day?" },
                { id: 4, name: "Emily Davis", email: "emilydavis@example.com", subject: "Re: Question about Budget", date: "2 days ago", teaser: "I've reviewed the budget numbers you sent over.\nCan we set up a quick call to discuss some potential adjustments?" },
                { id: 5, name: "Michael Wilson", email: "michaelwilson@example.com", subject: "Important Announcement", date: "1 week ago", teaser: "Please join us for an all-hands meeting this Friday at 3 PM.\nWe have some exciting news to share about the company's future." },
                { id: 6, name: "Sarah Brown", email: "sarahbrown@example.com", subject: "Re: Feedback on Proposal", date: "1 week ago", teaser: "Thank you for sending over the proposal. I've reviewed it and have some thoughts.\nCould we schedule a meeting to discuss my feedback in detail?" },
                { id: 7, name: "David Lee", email: "davidlee@example.com", subject: "New Project Idea", date: "1 week ago", teaser: "I've been brainstorming and came up with an interesting project concept.\nDo you have time this week to discuss its potential impact and feasibility?" },
                { id: 8, name: "Olivia Wilson", email: "oliviawilson@example.com", subject: "Vacation Plans", date: "1 week ago", teaser: "Just a heads up that I'll be taking a two-week vacation next month.\nI'll make sure all my projects are up to date before I leave." },
                { id: 9, name: "James Martin", email: "jamesmartin@example.com", subject: "Re: Conference Registration", date: "1 week ago", teaser: "I've completed the registration for the upcoming tech conference.\nLet me know if you need any additional information from my end." },
                { id: 10, name: "Sophia White", email: "sophiawhite@example.com", subject: "Team Dinner", date: "1 week ago", teaser: "To celebrate our recent project success, I'd like to organize a team dinner.\nAre you available next Friday evening? Please let me know your preferences." },
            ],
        }
    },
    methods: {
        chatSelected(chatID) {
            this.activeChat = chatID;
        }
    },
    computed: {
        selectedChat() {
            return this.activeChat ? this.mails.find(mail => mail.id === this.activeChat) : null
        }
    },
    provide() {
        return {
            user: {
                name: "shadcn",
                email: "m@example.com",
                avatar: "./images/shadcn.jpg",
            },
            mails : this.mails,
            navMain: [
                { title: "Inbox", url: "#", icon: Inbox, isActive: true },
                { title: "Drafts", url: "#", icon: File, isActive: false },
                { title: "Sent", url: "#", icon: Send, isActive: false },
                { title: "Junk", url: "#", icon: ArchiveX, isActive: false },
                { title: "Trash", url: "#", icon: Trash2, isActive: false },
            ],

        }
    }
};
</script>

<template>
    <SidebarProvider :style="{ '--sidebar-width': '350px', }">
        <AppSidebar @set-active-chat="chatSelected" />
        <SidebarInset>
            <header class="sticky top-0 flex shrink-0 items-center justify-between border-b bg-background p-4">
                <SidebarTrigger class="-ml-1" />
                <NavUser :user="user" class="w-[2rem] hidden md:block" />
            </header>
            <div class="flex flex-1 flex-col gap-4 p-4">
                <ChatBox :selectedChat="selectedChat" />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
