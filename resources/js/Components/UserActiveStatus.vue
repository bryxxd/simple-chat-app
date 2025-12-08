<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useChatStore } from "@/stores/chatStore";

const chatStore = useChatStore();

// Reactive variable
const userLeft = ref(chatStore.selectedUserDetails.last_active_at);


const checkIfHasInteracted = computed(() => {
    return chatStore.chatUsers.some(user => user.id === chatStore.selectedUserDetails?.id);
});

// Computed 
const lastActive = computed(() => {
    if (!userLeft.value || !checkIfHasInteracted.value) return '';

    const lastActiveDate = new Date(userLeft.value);
    const now = new Date();
    // Calculate the difference in milliseconds
    const diffInMs = now - lastActiveDate;
    // Calculate the difference in minutes
    const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
    // Calculate the difference in hours and days
    const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
    // Calculate the difference in days
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

    if (diffInMinutes < 60) {
        return `Active ${diffInMinutes} minute${diffInMinutes === 1 ? '' : 's'} ago`;
    } else if (diffInHours < 24) {
        return `Active ${diffInHours} hour${diffInHours === 1 ? '' : 's'} ago`;
    } else if (diffInDays < 7) {
        return `Active ${diffInDays} day${diffInDays === 1 ? '' : 's'} ago`;
    } else {
        return '';
    }
})

watch(
    () => chatStore.selectedUserDetails,
    (newVal) => {
        if (newVal) {
            userLeft.value = newVal.last_active_at;
        }
    }
);

</script>
<template>
    <div class="block">
        <p class="text-gray-500 text-sm">{{ lastActive }}</p>
    </div>
</template>
