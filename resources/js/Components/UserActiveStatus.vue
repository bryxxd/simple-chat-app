<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';


const currentDate = new Date();
const currentTime = currentDate.toLocaleTimeString();
const currentDateFormatted = currentDate.toLocaleDateString();

// Reactive variable
const userLeft = ref({
    time: '',
    date: ''
});



// Computed 
const lastActive = computed(() => {
    if(!userLeft.value.data) return;

    if(currentDateFormatted == userLeft.value.date) {
        return `Last active at ${userLeft.value.time}`
    } else {
        return `Last active on ${userLeft.value.date}`
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
        userLeft.value.data = res.data.last_active_at;
        userLeft.value.time = new Date(res.data.last_active_at).toLocaleTimeString();
        userLeft.value.date = new Date(res.data.last_active_at).toLocaleDateString();
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
        <p class="text-gray-500">{{ lastActive }}</p>
    </div>
</template>