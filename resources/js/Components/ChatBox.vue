<script setup>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/Components/ui/chat';
import { Textarea } from '@/Components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { usePage, Head } from "@inertiajs/vue3";
import UserActiveStatus from './UserActiveStatus.vue';
import { computed, watch, ref, useTemplateRef } from 'vue';
import axios from 'axios';
import { useChatStore } from "@/stores/chatStore";
import { useInfiniteScroll, useIntersectionObserver, useTemplateRefsList } from '@vueuse/core';
import { LoaderCircle } from "lucide-vue-next";

const chatStore = useChatStore();
const userIdReceiver = ref(null);
const isSending = ref(false);
const hasErrorSending = ref(false);
const chatListContainer = useTemplateRef('chatListContainer');
const chatItemRefs = useTemplateRefsList('chatItem');
const visibleMessageIds = ref([]);
const markedIds = ref(new Set());
let markAsReadTimeout = null;
let observerCleanup = null;

function setupVisibilityObserver() {
    // Clean up previous observer if it exists
    if (observerCleanup) {
        observerCleanup();
    }

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

// V-model
const formInput = defineModel();

// Computed
const authUser = computed(() => usePage().props.auth.user || {});

// Infinite scroll setup
const { isLoading } = useInfiniteScroll(
    chatListContainer,
    () => {
        // Load more messages
        chatStore.onLoadMore();
    },
    {
        distance: 30,
        direction: 'top',
        canLoadMore: () => chatStore.hasMorePages
    }
);

// Methods
async function sendMessage() {
    if (!userIdReceiver.value) {
        console.error('No active user selected');
        return;
    }

    if (isSending.value) return;

    const messageContent = formInput.value;
    isSending.value = true;

    try {
        await axios.post("/api/chat-room/send-message", {
            message: messageContent,
            receiver_id: userIdReceiver.value
        });

        chatStore.updateRecentChats({
            targetUserId: userIdReceiver.value,
            content: messageContent,
            sender_id: authUser.value?.id,
        });

        formInput.value = '';
    } catch (err) {
        console.error('Failed to send message:', err);
        hasErrorSending.value = true;
    } finally {
        isSending.value = false;
    }
}

function isSender(chat) {
    return authUser.value && chat.sender_id === authUser.value.id;
}

function getUserAvatar(chat) {
    return authUser.value && chat.sender_id === authUser.value.id
        ? authUser.value.avatar
        : chatStore.messagesData?.participant?.avatar
}

// Watch for selectedUserDetails changes
watch(() => chatStore.selectedUserDetails, (newActiveUser) => {
    if (newActiveUser?.id) {
        userIdReceiver.value = newActiveUser.id;
        setupVisibilityObserver();
        visibleMessageIds.value = [];
    }
}, { immediate: true });

const pageTitle = computed(() => {
    return chatStore.selectedUserDetails.first_name + ' ' + chatStore.selectedUserDetails.last_name;
})


</script>
<template>

    <Head :title="pageTitle" />
    <Chat>
        <ChatDetails>
            <div class="flex">
                <ChatAvatar class="w-12 h-12 md:w-14 md:h-14" :src="chatStore.messagesData?.participant?.avatar" />
                <div class="flex flex-col justify-between ml-4">
                    <ChatName>{{ chatStore.messagesData?.participant?.first_name }} {{
                        chatStore.messagesData?.participant?.last_name }}
                    </ChatName>
                    <ChatStatus v-if="chatStore.isOnline(chatStore.selectedUserDetails?.id)" class="text-green-700">
                        Online</ChatStatus>
                    <UserActiveStatus v-else :id="chatStore.selectedUserDetails?.id" />
                </div>
            </div>
        </ChatDetails>
        <ChatContent ref="chatListContainer">
            <template v-if="chatStore.loadError">
                <div class="text-red-500 text-center">{{ chatStore.loadError }}</div>
            </template>
            <template v-else>
                <ChatList>
                    <template v-if="isLoading">
                        <LoaderCircle class="animate-spin mx-auto w-8 h-8" />
                    </template>
                    <div v-for="chat in chatStore.messagesData.messages" :key="chat.id" :data-mid="chat.id"
                        class="flex gap-4" :ref="chatItemRefs.set" :class="{ 'flex-row-reverse': isSender(chat) }">
                        <ChatAvatar :src="getUserAvatar(chat)" class="w-8 h-8" />
                        <ChatMessage :variant="isSender(chat) ? 'sender' : 'default'">
                            {{ chat.content }}
                        </ChatMessage>
                    </div>
                    <template v-if="hasErrorSending">
                        <div class="text-red-500 text-sm text-right">Failed to send message. Please try again.</div>
                    </template>
                </ChatList>
            </template>
        </ChatContent>
        <ChatForm @submit.prevent="sendMessage" method="POST" v-if="!chatStore.loadError">
            <Textarea placeholder="Type your message..." v-model="formInput" :disabled="isSending" class="pr-20" />
            <Button class="absolute right-[0.5rem] top-[0.7rem]" type="submit"
                :disabled="isSending || !formInput?.trim()">
                <span v-if="isSending">
                    <LoaderCircle class="animate-spin" />
                </span>
                <template v-else>
                    <Send />
                </template>
            </Button>
        </ChatForm>
    </Chat>
</template>
