<script setup>
import { defineProps, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useChatStore } from '@/stores/chatStore';

const chatStore = useChatStore();

// Props
const props = defineProps({
    user : {
        type: Object,
        required: true
    }
})

// Computed properties
const userAvatarSrc = computed(() => {
    return props.user?.avatar || '/images/profile-placeholder.jpg';
})

// Navigation handler
const handleUserClick = () => {
    const userId = props.user?.id;
    const currentRoute = usePage().url;

    // Update the selected user first
    chatStore.updateSelectedUser(userId);
    
    // If not on dashboard, navigate there
    if (currentRoute !== '/' && !currentRoute.startsWith('/?')) {
        router.visit('/', {
            preserveState: true,
            preserveScroll: false,
            replace: false
        });
    }
}

</script>
<template>
    <div @click="handleUserClick" class="flex items-center gap-2 whitespace-nowrap p-4 text-sm leading-tight last:border-b-0 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer">
        <slot>
            <div class="relative">
                <img class="rounded-full w-12 h-12" :src="userAvatarSrc" alt="">
                <span v-if="chatStore.isOnline(user?.id)" class="w-2 h-2 bg-green-700 inline-block rounded-full absolute right-2 bottom-0"></span>
            </div>
            <div class="flex flex-col gap-3 justify-between w-48 overflow-hidden">
                <h3 class="text-ellipsis overflow-hidden whitespace-nowrap">{{ user?.first_name }} {{ user?.last_name }}</h3>
                <p class="text-ellipsis overflow-hidden whitespace-nowrap" :class="{ 'font-bold' : !user?.is_read}">{{ user?.content }}</p>
            </div>
        </slot>
    </div>
</template>
