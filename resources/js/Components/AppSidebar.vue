<script setup>
import { computed, inject } from 'vue';
import { Link } from "@inertiajs/vue3";
import SearchUser from "@/components/SearchUser.vue";
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
} from "@/components/ui/sidebar";
import Room from "@/components/Room.vue";
import EmptyRoom from '@/Components/EmptyRoom.vue';

// Props
const props = defineProps({
    side: { type: String, required: false },
    variant: { type: String, required: false },
    collapsible: { type: String, required: false, default: "icon" },
    class: { type: null, required: false },
});

// Inject
const interactedUsers = inject("interactedUsers");

const hasInteraction = computed(() => {
    return interactedUsers && interactedUsers.length > 0 ? true : false
})

const { updateActiveChat } = inject('activeChat');

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
                            <Room v-for="user in interactedUsers" :key="user.id" :user="user"
                                @click="updateActiveChat(user.id)" />
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
