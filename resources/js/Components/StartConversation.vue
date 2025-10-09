<script setup>
import { Avatar, AvatarImage } from "@/components/ui/avatar";
import { Search, Sparkles } from "lucide-vue-next";
import { Input } from '@/components/ui/input';
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useChatStore } from "@/stores/chatStore";

const chatStore = useChatStore();
const users = computed(() => usePage().props.users);
const getUserAvatar = (user) => user?.avatar || '/images/profile-placeholder.jpg';
</script>
<template>
    <div>
        <div class="relative w-full p-6 border-b border-gray-200">
            <Input id="search" type="text" placeholder="Search..." class="pl-10 py-6 w-full" />
            <span class="absolute left-6 inset-y-0 flex items-center justify-center px-2">
                <Search class="size-6 text-muted-foreground" />
            </span>
        </div>
        <div class="p-6">
            <h3 class="inline-flex items-center gap-4 text-lg">
                <Sparkles class="text-yellow-500" />People you may know
            </h3>
            <div class="space-y-2 mt-6">
                <div v-for="user in users"
                    class="flex items-center gap-4 p-3 bg-background rounded-lg border border-gray-200 hover:shadow-sm transition-all hover:border-gray-300">
                    <Avatar>
                        <AvatarImage :src="getUserAvatar(user)" alt="" />
                    </Avatar>
                    <div class="flex-1">
                        <h4 class="font-medium text-background-900">{{ user?.first_name }} {{ user?.last_name }}</h4>
                    </div><button @click="chatStore.updateSelectedUser(user.id)"
                        class="bg-primary hover:bg-primary/90 text-primary-foreground px-4 py-2 rounded-full text-sm transition-colors">Message</button>
                </div>
            </div>
        </div>
    </div>

</template>