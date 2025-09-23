<script setup>
import AppSidebar from "@/Components/AppSidebar.vue";
import ChatBox from "@/Components/ChatBox.vue";
import NavUser from "@/Components/NavUser.vue";
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/Components/ui/sidebar";
import { Head, Link } from "@inertiajs/vue3";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
import { ref, computed, provide } from "vue";
import EmptyChatBox from "@/Components/EmptyChatBox.vue";
import StartConversation from "@/Components/StartConversation.vue";
import { useChatManager } from "@/composables/useChatManager";

const {
    activeUser,
    chatUsers,
} = useChatManager();

// No interaction components
const currentNoInteractionTab = ref('EmptyChatBox');
const noInteractionTabs = {
    EmptyChatBox,
    StartConversation
};
function switchNoInteractionTab(tabName) {
    if (noInteractionTabs[tabName]) {
        currentNoInteractionTab.value = tabName;
    }
}
const checkNoInteractionTab = computed(() => {
    return currentNoInteractionTab.value === 'StartConversation' && activeUser.value === null ? true : false;
});

// Provide
provide('noInteractionTabs', { noInteractionTabs, switchNoInteractionTab });
</script>

<template>
    <Head title="Chat" />
    <SidebarProvider :style="{ '--sidebar-width': '350px' }">
        <AppSidebar />
        <SidebarInset>
            <header class="sticky top-0 flex shrink-0 items-center border-b bg-background p-2 md:p-4 z-50">
                <div class="flex items-center">
                    <SidebarTrigger class="-ml-1" />
                </div>
                <Breadcrumb class="mx-4 flex-1">
                    <BreadcrumbList>
                        <BreadcrumbItem>
                            <Link class="transition-colors hover:text-foreground" :href="route('dashboard')">Home</Link>
                        </BreadcrumbItem>
                        <slot name="currentPage"></slot>
                        <template v-if="checkNoInteractionTab">
                            <BreadcrumbSeparator class="hidden md:block" />
                            <BreadcrumbItem>
                                <BreadcrumbPage>Start new conversation</BreadcrumbPage>
                            </BreadcrumbItem>
                        </template>
                    </BreadcrumbList>
                </Breadcrumb>
                <NavUser class="w-[3rem] md:w-[2rem]" />
            </header>
            <slot>
                <template v-if="chatUsers && chatUsers.length > 0 || activeUser">
                    <ChatBox />
                </template>
                <template v-else>
                    <component :is="noInteractionTabs[currentNoInteractionTab]"></component>
                </template>
            </slot>
        </SidebarInset>
    </SidebarProvider>
</template>
