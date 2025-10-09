<script setup>
import { computed } from 'vue';
import { Link } from "@inertiajs/vue3";
import SearchUser from "@/Components/SearchUser.vue";
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
} from "@/Components/ui/sidebar";
import Room from "@/Components/Room.vue";
import EmptyRoom from '@/Components/EmptyRoom.vue';
import { useChatStore } from '@/stores/chatStore';

const chatStore = useChatStore();

const hasInteraction = computed(() => {
    return chatStore.chatUsers && chatStore.chatUsers.length > 0;
});

// Props
const props = defineProps({
    side: { type: String, required: false },
    variant: { type: String, required: false },
    collapsible: { type: String, required: false, default: "icon" },
    class: { type: null, required: false },
});
</script>

<template>
    <Sidebar class="overflow-hidden [&>[data-sidebar=sidebar]]:flex-row" v-bind="$props">
        <Sidebar collapsible="none" class="flex-1 flex">
            <SidebarHeader class="gap-3.5 border-b p-4">
                <div class="flex w-full items-center justify-between">
                    <Link :href="route('dashboard')" class="text-base font-medium text-foreground">Logo</Link>
                </div>
                <!-- Search User -->
                <SearchUser />
            </SidebarHeader>
            <SidebarContent :class="{ 'justify-center': !hasInteraction }">
                <SidebarGroup class="px-0">
                    <SidebarGroupContent>
                        <template v-if="hasInteraction">
                            <Room v-for="user in chatStore.chatUsers" :key="user.id" :user="user" />
                        </template>
                        <template v-else>
                            <EmptyRoom />
                        </template>
                    </SidebarGroupContent>
                </SidebarGroup>
            </SidebarContent>
        </Sidebar>
    </Sidebar>
</template>
