<script>
import { Command } from "lucide-vue-next";
import NavUser from "@/components/NavUser.vue";
import { Label } from "@/components/ui/label";
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarHeader,
    SidebarInput,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@/components/ui/sidebar";
import { Switch } from "@/components/ui/switch";
import { h } from "vue";

export default {
    name: "AppSidebar",
    components: {
        Command,
        NavUser,
        Label,
        Sidebar,
        SidebarContent,
        SidebarFooter,
        SidebarGroup,
        SidebarGroupContent,
        SidebarHeader,
        SidebarInput,
        SidebarMenu,
        SidebarMenuButton,
        SidebarMenuItem,
        Switch,
    },
    props: {
        side: { type: String, required: false },
        variant: { type: String, required: false },
        collapsible: { type: String, required: false, default: "icon" },
        class: { type: null, required: false },
    },
    inject: ["navMain", "mails"],
    data() {
        return {
            activeItem: null,
            filteredMails: [],
        };
    },
    created() {
        this.activeItem = this.navMain[0];
        this.filteredMails = this.mails;
    },
    methods: {
        handleMenuClick(item) {
            this.activeItem = item;
            // Shuffle mails and pick a random number between 5 and 10
            const shuffled = [...this.mails].sort(() => Math.random() - 0.5);
            this.filteredMails = shuffled.slice(
                0,
                Math.max(5, Math.floor(Math.random() * 10) + 1),
            );
            this.setSidebarOpen(true);
        },
        setSidebarOpen(val) {
            const { setOpen } = useSidebar();
            setOpen(val);
        },
        renderTooltip(title) {
            return h("div", { hidden: false }, title);
        },
        setActiveChat(id) {
          this.$emit("setActiveChat", id);
        }

    },
};
</script>

<template>
    <Sidebar
        class="overflow-hidden [&>[data-sidebar=sidebar]]:flex-row"
        v-bind="$props"
    >
        <!-- This is the first sidebar -->
        <Sidebar
            collapsible="none"
            class="!w-[calc(var(--sidebar-width-icon)_+_1px)] border-r"
        >
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            size="lg"
                            as-child
                            class="md:h-8 md:p-0"
                        >
                            <a href="#">
                                <div
                                    class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground"
                                >
                                    <Command class="size-4" />
                                </div>
                                <div
                                    class="grid flex-1 text-left text-sm leading-tight"
                                >
                                    <span class="truncate font-semibold"
                                        >Acme Inc</span
                                    >
                                    <span class="truncate text-xs"
                                        >Enterprise</span
                                    >
                                </div>
                            </a>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <SidebarGroup>
                    <SidebarGroupContent class="px-1.5 md:px-0">
                        <SidebarMenu>
                            <SidebarMenuItem
                                v-for="item in navMain"
                                :key="item.title"
                            >
                                <SidebarMenuButton
                                    :tooltip="renderTooltip(item.title)"
                                    :is-active="
                                        activeItem &&
                                        activeItem.title === item.title
                                    "
                                    class="px-2.5 md:px-2"
                                    @click="handleMenuClick(item)"
                                >
                                    <component :is="item.icon" />
                                    <span>{{ item.title }}</span>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>
            </SidebarContent>
            <SidebarFooter>
                <NavUser class="block md:hidden" />
            </SidebarFooter>
        </Sidebar>

        <!--  This is the second sidebar -->
        <Sidebar collapsible="none" class="hidden flex-1 md:flex">
            <SidebarHeader class="gap-3.5 border-b p-4">
                <div class="flex w-full items-center justify-between">
                    <div class="text-base font-medium text-foreground">
                        {{ activeItem ? activeItem.title : "" }}
                    </div>
                    <Label class="flex items-center gap-2 text-sm">
                        <span>Unreads</span>
                        <Switch class="shadow-none" />
                    </Label>
                </div>
                <SidebarInput placeholder="Type to search..." />
            </SidebarHeader>
            <SidebarContent>
                <SidebarGroup class="px-0">
                    <SidebarGroupContent>
                        <a
                            v-for="mail in filteredMails"
                            :key="mail.id"
                            @click="setActiveChat(mail.id)"
                            href="#"
                            class="flex flex-col items-start gap-2 whitespace-nowrap border-b p-4 text-sm leading-tight last:border-b-0 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground"
                        >
                            <div class="flex w-full items-center gap-2">
                                <span>{{ mail.name }}</span>
                                <span class="ml-auto text-xs">{{
                                    mail.date
                                }}</span>
                            </div>
                            <span class="font-medium">{{ mail.subject }}</span>
                            <span
                                class="line-clamp-2 w-[260px] whitespace-break-spaces text-xs"
                            >
                                {{ mail.teaser }}
                            </span>
                        </a>
                    </SidebarGroupContent>
                </SidebarGroup>
            </SidebarContent>
        </Sidebar>
    </Sidebar>
</template>
