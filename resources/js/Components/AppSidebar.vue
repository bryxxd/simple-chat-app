<script>
import { Link } from "@inertiajs/vue3";
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarInput,
} from "@/components/ui/sidebar";
import { Room } from "@/components/ui/room";
import { Switch } from "@/components/ui/switch";
import { Label } from "@/components/ui/label";

export default {
    name: "AppSidebar",
    components: {
        Link,
        Sidebar,
        SidebarContent,
        SidebarGroup,
        SidebarGroupContent,
        SidebarHeader,
        SidebarInput,
        Room,
        Switch,
        Label
    },
    emits : ["set-active-chat"],
    props: {
        side: { type: String, required: false },
        variant: { type: String, required: false },
        collapsible: { type: String, required: false, default: "icon" },
        class: { type: null, required: false },
    },
    inject: ["chats"],
    data() {
        return {
            title: "Logo"
        };
    },
    methods: {
        setActiveChat(id) {
            this.$emit("set-active-chat", id);
        }
    },
};
</script>

<template>
    <Sidebar class="overflow-hidden [&>[data-sidebar=sidebar]]:flex-row" v-bind="$props">
        <Sidebar collapsible="none" class="flex-1 flex">
            <SidebarHeader class="gap-3.5 border-b p-4">
                <div class="flex w-full items-center justify-between">
                    <Link :href="route('dashboard')" class="text-base font-medium text-foreground">{{ title }}</Link>
                    <Label class="flex items-center gap-2 text-sm">
                        <span>Unreads</span>
                        <Switch class="shadow-none" />
                    </Label>
                </div>
                <SidebarInput placeholder="Type to search..." />
            </SidebarHeader>
            <SidebarContent class="hidden md:block">
                <SidebarGroup class="px-0">
                    <SidebarGroupContent>
                        <Room v-for="chat in chats" :key="chat.id" :chat="chat" @click="setActiveChat(chat.id)"></Room>
                    </SidebarGroupContent>
                </SidebarGroup>
            </SidebarContent>
        </Sidebar>
    </Sidebar>
</template>
