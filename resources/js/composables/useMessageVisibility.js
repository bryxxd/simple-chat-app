import { ref } from 'vue';
import { useIntersectionObserver } from '@vueuse/core';
import axios from 'axios';
import { useChatStore } from '@/stores/chatStore';

export function useMessageVisibility(chatItemRefs, userIdReceiver) {
    const chatStore = useChatStore();
    const visibleMessageIds = ref([]);
    const markedIds = ref(new Set());
    let markAsReadTimeout = null;
    let observerCleanup = null;

    function cleanupObserver() {
        if (observerCleanup) {
            observerCleanup();
        }
    }

    function scheduleMarkAsRead() {
        if (markAsReadTimeout) {
            clearTimeout(markAsReadTimeout);
        }

        markAsReadTimeout = setTimeout(() => {
            const messageIdsToMark = Array.from(visibleMessageIds.value);
            markedIds.value = new Set([...markedIds.value, ...messageIdsToMark]);
            if (messageIdsToMark.length > 0) {
                markedAsRead(messageIdsToMark);
                visibleMessageIds.value = [];
            }
        }, 2000); // 2 seconds delay
    }

    async function markedAsRead(chatIds) {
        try {
            await axios.get("/api/chat-room/marked-as-read/", {
                params: {
                    message_ids: chatIds
                }
            });
            // Update the read status in the store for the current user
            if (userIdReceiver.value) {
                chatStore.updateMessageReadStatus(userIdReceiver.value);
            }
        } catch (err) {
            console.error('Failed to mark messages as read:', err);
        }
    }

    function setupVisibilityObserver() {
        cleanupObserver();

        if (markedIds.value.size > 0) {
            // Filter out already marked IDs
            visibleMessageIds.value = visibleMessageIds.value.filter(id => !markedIds.value.has(id));
        }

        const { stop } = useIntersectionObserver(chatItemRefs, (entries) => {
            entries.forEach(entry => {
                const messageId = entry.target.getAttribute('data-mid');
                // Skip if already marked or already in visible list
                if (markedIds.value.has(messageId)) {
                    return;
                }
                if (entry.isIntersecting && !visibleMessageIds.value.includes(messageId)) {
                    visibleMessageIds.value.push(messageId);
                    scheduleMarkAsRead();
                }
            });
        }, {
            threshold: 1,
        });

        observerCleanup = stop;
    }

    function resetVisibility() {
        visibleMessageIds.value = [];
        setupVisibilityObserver();
    }

    return {
        setupVisibilityObserver,
        resetVisibility,
        cleanupObserver
    };
}
