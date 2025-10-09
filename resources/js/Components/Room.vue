<script setup>
import { defineProps, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useChatManager } from '@/composables/useChatManager';
const { isOnline, updateSelectedUser } = useChatManager();

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
    console.log('Room clicked, user ID:', userId);
    
    const currentRoute = usePage().url;
    console.log('Current route:', currentRoute);
    
    // If we're already on dashboard, just update the selected user
    if (currentRoute === '/') {
        updateSelectedUser(userId);
        console.log('Already on dashboard, user set to:', userId);
        return;
    }
    
    // If we're on a different page, navigate to dashboard first
    // The selected user will be set after navigation
    router.visit('/', {
        preserveState: false,
        preserveScroll: false,
        replace: false,
        onSuccess: () => {
            // Set the selected user after successful navigation
            console.log('Navigation successful, setting user:', userId);
            updateSelectedUser(userId);
        },
        onError: (errors) => {
            console.error('Navigation failed:', errors);
        }
    });
}

</script>
<template>
    <div @click="handleUserClick" class="flex items-center gap-2 whitespace-nowrap p-4 text-sm leading-tight last:border-b-0 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer">
        <slot>
            <div class="relative">
                <img class="rounded-full w-12 h-12" :src="userAvatarSrc" alt="">
                <span v-if="isOnline(user?.id)" class="w-2 h-2 bg-green-700 inline-block rounded-full absolute right-2 bottom-0"></span>
            </div>
            <div class="flex flex-col gap-3 justify-between w-48 overflow-hidden">
                <h3 class="text-ellipsis overflow-hidden whitespace-nowrap">{{ user?.first_name }} {{ user?.last_name }}</h3>
                <p class="text-ellipsis overflow-hidden whitespace-nowrap">{{ user?.content }}</p>
            </div>
        </slot>
    </div>
</template>