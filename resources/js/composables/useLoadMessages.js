import { ref, toValue } from "vue";
import axios from "axios";

export function useLoadMessages() {
    const totalMessages = ref(0);
    const perPage = ref(50);
    const currentPage = ref(1);
    const messagesData = ref({
        messages: [],
        participant: null,
        totalMessages: 0,
    });
    const loading = ref(false);
    const allMessagesLoaded = ref(false);

    async function loadMessages(userId) {
        const userIdValue = toValue(userId);
        if (loading.value || allMessagesLoaded.value) return;
        loading.value = true;

        try {
            const res = await axios.get(`/api/chat-room/${userIdValue}`);
            if (res.data && Array.isArray(res.data.messages)) {
                // Sort messages by ID to ensure chronological order
                const sortedMessages = res.data.messages.sort((a, b) => a.id - b.id);
                
                // Replace messages instead of appending for new user
                messagesData.value.messages = sortedMessages;
                messagesData.value.participant = res.data.participant;
                messagesData.value.totalMessages = res.data.totalMessages;
                totalMessages.value = res.data.totalMessages;
            }
        } catch (error) {
            console.error("Error loading messages:", error);
        } finally {
            loading.value = false;
        }
    }


    return {
        totalMessages,
        perPage,
        currentPage,
        messagesData,
        loading,
        allMessagesLoaded,
        loadMessages,
    };
}
