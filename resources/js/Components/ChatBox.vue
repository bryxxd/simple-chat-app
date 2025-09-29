<script setup>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { usePage } from "@inertiajs/vue3";
import UserActiveStatus from './UserActiveStatus.vue';
import { computed, watch, ref } from 'vue';
import axios from 'axios';
import { useChatManager } from "@/composables/useChatManager";

const { isOnline, updateRecentChats, selectedUserDetails, totalMessages, messagesData, hasMoreMessages } = useChatManager();

const userIdReceiver = ref(null);
const isSending = ref(false);
const hasErrorSending = ref(false);

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
        updateRecentChats({
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
        : messagesData?.value?.participant?.avatar
}

// Watch for selectedUserDetails changes and update form
watch(() => selectedUserDetails?.value, (newActiveUser) => {
    if (newActiveUser?.id) {
        userIdReceiver.value = newActiveUser.id;
    }
}, { immediate: true });

</script>
<template>
    <Chat>
        <ChatDetails>
            <div class="flex">
                <ChatAvatar :src="messagesData?.participant?.avatar" />
                <div class="flex flex-col justify-between ml-4">
                    <ChatName>{{ messagesData?.participant?.first_name }} {{ messagesData?.participant?.last_name }}
                    </ChatName>
                    <ChatStatus v-if="isOnline(selectedUserDetails?.id)" class="text-green-700">Online</ChatStatus>
                    <UserActiveStatus v-else :id="selectedUserDetails?.id" />
                </div>
            </div>
        </ChatDetails>
        <ChatContent ref="chatContent">
            <ChatList ref="chatList">
                <template v-if="hasMoreMessages">
                    <div class="animate-pulse w-full text-center">Loading...</div>
                </template>
                <ChatItem v-for="(chat, index) in messagesData.messages" :key="index"
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
        </ChatContent>
        <ChatForm @submit.prevent="sendMessage" method="POST">
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