<script setup>
import { Link } from "@inertiajs/vue3";
import { Toaster } from "@/Components/ui/toast";
import { defineProps } from "vue";

// Props
const props = defineProps({
    side: { type: String, required: false },
    variant: { type: String, required: false },
    collapsible: { type: String, required: false, default: "icon" },
    class: { type: null, required: false },
});

// Links for the settings sidebar
const links = [
    { name: "Profile", href: "/settings/profile" },
    { name: "Password", href: "/settings/password" },       
    { name: "Appearance", href: "/settings/appearance" },
];
</script>

<template>
    <Toaster />
    <div class="space-y-6 px-5 pt-10 md:p-10 pb-16 md:block">
        <div class="space-y-0.5">
            <h2 class="text-2xl font-bold tracking-tight">Settings</h2>
            <p class="text-muted-foreground">Manage your profile and account settings.</p>
        </div>
        <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full my-6"></div>
        <div class="flex flex-col space-y-8 lg:flex-row lg:space-x-12 lg:space-y-0">
            <aside class=" md:-mx-4 lg:w-1/5">
                <nav class="block md:flex lg:flex-col lg:space-x-0 space-y-0 lg:space-y-1">
                    <Link v-for="link in links" class="inline-flex items-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&amp;_svg]:pointer-events-none [&amp;_svg]:size-4 [&amp;_svg]:shrink-0 hover:text-accent-foreground h-9 px-4 py-2 w-full text-left justify-start hover:bg-muted"
                        :href="link.href" :class="{ 'bg-muted' : $page.url == link.href }">{{ link.name }}</Link>
                </nav>
            </aside>
            <div class="flex-1 lg:max-w-2xl">
                <div class="space-y-6">
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>
