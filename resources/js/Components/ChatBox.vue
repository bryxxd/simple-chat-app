<script setup>
import { useElementVisibility } from '@vueuse/core';
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/Components/ui/chat';
import { Textarea } from '@/Components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { usePage } from "@inertiajs/vue3";
import UserActiveStatus from './UserActiveStatus.vue';
import { computed, watch, ref, useTemplateRef } from 'vue';
import axios from 'axios';
import { useChatStore } from "@/stores/chatStore";

const chatStore = useChatStore();

const userIdReceiver = ref(null);
const isSending = ref(false);
const hasErrorSending = ref(false);
const target = useTemplateRef('target');
const targetIsVisible = useElementVisibility(target, { threshold: 0.1 });

// V-model
const formInput = defineModel();

// Computed
const authUser = computed(() => usePage().props.auth.user || {});

// Methods
async function sendMessage() {
    if (!userIdReceiver.value) {
        console.error('No active user selected');
        return;
    }

    if (!formInput.value?.trim()) {
        console.warn('Cannot send empty message');
        return;
    }

    if (isSending.value) return;

    // Store the content before clearing it
    const messageContent = formInput.value;
    isSending.value = true;

    try {
        await axios.post("/api/send-message", {
            message: messageContent,
            to_user_id: userIdReceiver.value
        });

        // Use the stored content - only update on success
        chatStore.updateRecentChats({
            targetUserId: userIdReceiver.value,
            content: messageContent
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
    return authUser.value && chat.from_user_id === authUser.value.id;
}

function getUserAvatar(chat) {
    return authUser.value && chat.from_user_id === authUser.value.id
        ? authUser.value.avatar
        : chatStore.messagesData?.participant?.avatar
}

// Watch for selectedUserDetails changes and update form
watch(() => chatStore.selectedUserDetails, (newActiveUser) => {
    if (newActiveUser?.id) {
        userIdReceiver.value = newActiveUser.id;
    }
}, { immediate: true });

// Watch loading message to load more messages when needed
// watch(targetIsVisible, (isVisible) => {
//     try {
//         const userId = chatStore.selectedUserDetails?.id;
//         const hasMore = chatStore.hasMoreMessages;

//         if (isVisible && userId && hasMore) {
//             chatStore.loadMoreMessages();
//         }
//     } catch (error) {
//         console.error('Error in targetIsVisible watcher:', error);
//     }
// });

</script>
<template>
    <Chat>
        <ChatDetails>
            <div class="flex">
                <ChatAvatar :src="chatStore.messagesData?.participant?.avatar" />
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
        <ChatContent ref="chatContent">
            <template class="p-4" v-if="chatStore.loadError">
                <div class="text-red-500 text-center">{{ chatStore.loadError }}</div>
            </template>
            <template v-else>
                <ChatList ref="chatList">
                    <template v-if="chatStore.hasMoreMessages">
                        <div class="animate-pulse w-full text-center" ref="target">
                            Loading...</div>
                    </template>
                    <ChatItem v-for="(chat, index) in chatStore.messagesData.messages" :key="index"
                        :class="{ 'flex-row-reverse': isSender(chat) }">
                        <ChatAvatar :src="getUserAvatar(chat)" class="w-8 h-8" />
                        <ChatMessage :variant="isSender(chat) ? 'sender' : 'default'">
                            {{ chat.content }}
                        </ChatMessage>
                    </ChatItem>
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
                <span v-if="isSending">Sending...</span>
                <template v-else>
                    Send
                    <Send />
                </template>
            </Button>
        </ChatForm>
    </Chat>
</template>