<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

// Reactive variable
const userLeft = ref({
    last_active_at: null
});

// Computed 
const lastActive = computed(() => {
    const lastActiveDate = new Date(userLeft.value.last_active_at);
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
    } else if(diffInHours < 24) {
        return `Active ${diffInHours} hour${diffInHours === 1 ? '' : 's'} ago`;
    } else if (diffInDays < 7) {
        return `Active ${diffInDays} day${diffInDays === 1 ? '' : 's'} ago`;
    } else {
        return '';
    }
})

// Props
const props = defineProps({
    id: {
        type: Number,
        required: true
    }
})

async function fetchUserLeftAt(user) {
    try {
        const res = await axios.get(`/api/get-last-active/${user}`);
        userLeft.value = {
            last_active_at: res.data.last_active_at
        };
    } catch (error) {
        console.error('Error fetching user left time:', error);
        console.log('Error details:', error.response?.data); // Debug log
    }
}

onMounted(() => {
    fetchUserLeftAt(props.id);
})

</script>
<template>
    <div class="block">
        <p class="text-gray-500 text-sm">{{ lastActive }}</p>
    </div>
</template>