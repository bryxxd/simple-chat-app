<script setup>
import AppSidebar from "@/Components/AppSidebar.vue";
import Loading from "@/Components/LoadingChatBox.vue";
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
import { ref, computed, provide, defineAsyncComponent, onMounted } from "vue";
import EmptyChatBox from "@/Components/EmptyChatBox.vue";
import StartConversation from "@/Components/StartConversation.vue";
import { useChatStore } from "@/stores/chatStore";

const chatStore = useChatStore();

// Initialize the chat store when component mounts
onMounted(() => {
    chatStore.initializeFromProps();
    chatStore.setupEchoListeners();
});

// No interaction components
const currentNoInteractionTab = ref('EmptyChatBox');
const noInteractionTabs = {
    EmptyChatBox,
    StartConversation
};
function switchNoInteractionTab(tabName) {
    if (noInteractionTabs[tabName]) {
        currentNoInteractionTab.value = tabName;
       // Clear active user when switching to no interaction tab
        chatStore.updateSelectedUser(null);
    }
}
const checkNoInteractionTab = computed(() => {
    return currentNoInteractionTab.value === 'StartConversation' && chatStore.selectedUserDetails === null ? true : false;
});

// Provide
provide('noInteractionTabs', { noInteractionTabs, switchNoInteractionTab });

const AsyncChatBox = defineAsyncComponent({
    loader: () => import('@/Components/ChatBox.vue'),
    loadingComponent: Loading, 
    timeout: 10000, 
    errorComponent: Loading, 
});

</script>

<template>
    <Head :title="!chatStore.selectedUserDetails ? 'Start Chat' : ''" />
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
                <template v-if="chatStore.selectedUserDetails">
                    <AsyncChatBox />
                </template>
                <template v-else>
                    <component :is="noInteractionTabs[currentNoInteractionTab]"></component>
                </template>
            </slot>
        </SidebarInset>
    </SidebarProvider>
</template>
