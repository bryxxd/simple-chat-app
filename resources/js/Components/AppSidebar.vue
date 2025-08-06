<script setup>
import { inject } from 'vue';
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
import { Switch } from "@/components/ui/switch";
import { Label } from "@/components/ui/label";

// Props
const props = defineProps({
    side: { type: String, required: false },
    variant: { type: String, required: false },
    collapsible: { type: String, required: false, default: "icon" },
    class: { type: null, required: false },
});

// Emits
const emit = defineEmits(['set-active-chat']);

// Inject
const interactedUsers = inject("interactedUsers");

// Methods
function setActiveChat(id) {
    emit("set-active-chat", id);
}

</script>

<template>
    <Sidebar class="overflow-hidden [&>[data-sidebar=sidebar]]:flex-row" v-bind="$props">
        <Sidebar collapsible="none" class="flex-1 flex">
            <SidebarHeader class="gap-3.5 border-b p-4">
                <div class="flex w-full items-center justify-between">
                    <Link :href="route('dashboard')" class="text-base font-medium text-foreground">Logo</Link>
                    <Label class="flex items-center gap-2 text-sm">
                        <span>Unreads</span>
                        <Switch class="shadow-none" />
                    </Label>
                </div>
                <!-- Search User -->
                <SearchUser @set-active-chat="setActiveChat" />
            </SidebarHeader>
            <SidebarContent>
                <SidebarGroup class="px-0">
                    <SidebarGroupContent>
                        <Room v-for="user in interactedUsers" :key="user.id" :user="user"
                            @click="setActiveChat(user.id)"></Room>
                    </SidebarGroupContent>
                </SidebarGroup>
            </SidebarContent>
        </Sidebar>
    </Sidebar>
</template>
