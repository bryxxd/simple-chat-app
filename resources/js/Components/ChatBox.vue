<script setup>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { usePage } from "@inertiajs/vue3";
import UserActiveStatus from './UserActiveStatus.vue';
import { computed, watch, reactive, ref } from 'vue';
import axios from 'axios';
import { useChatManager } from "@/composables/useChatManager";
import { useLoadMessages } from '@/composables/useLoadMessages';

const { isOnline, updateRecentChats: moveUserToTop, selectedUserDetails: activeUser, messagesData, totalMessages } = useChatManager();

const userIdReceiver = ref(null);
// V-model
const formInput = defineModel();

// Computed
const authUser = computed(() => usePage().props.auth.user || {});

const sortedMessages = computed(() => {
    return messagesData.value.messages.sort((a, b) => a.id - b.id);
});


const hasMoreMessages = computed(() => {
    return messagesData.value.messages.length < totalMessages.value;
});


// Methods
async function sendMessage() {
    if (!userIdReceiver.value) {
        console.error('No active user selected');
        return;
    }

    // Store the content before clearing it
    const messageContent = formInput.value;

    try {
        await axios.post("/api/send-message", {
            message: messageContent,
            to_user_id: userIdReceiver.value
        });
    } catch (err) {
        console.log(err);
    } finally {
        formInput.value = '';
    }

    // Use the stored content
    moveUserToTop({
        targetUserId: userIdReceiver.value,
        content: messageContent
    });
}

function isSender(chat) {
    return authUser.value && chat.from_user_id === authUser.value.id;
}

function getUserAvatar(chat) {
    return authUser.value && chat.from_user_id === authUser.value.id
        ? authUser.value.avatar
        : messagesData?.value?.participant?.avatar
}

// Watch for activeUser changes and update form
watch(() => activeUser?.value, (newActiveUser) => {
    if (newActiveUser?.id) {
        userIdReceiver.value = newActiveUser.id;

        console.log(hasMoreMessages.value)
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
                    <ChatStatus v-if="isOnline(activeUser?.id)" class="text-green-700">Online</ChatStatus>
                    <UserActiveStatus v-else :id="activeUser?.id" />
                </div>
            </div>
        </ChatDetails>
        <ChatContent ref="chatContent">
            <ChatList ref="chatList">
                <template v-if="hasMoreMessages">
                    <div class="animate-pulse w-full text-center">Loading...</div>
                </template>
                <ChatItem v-for="(chat, index) in sortedMessages" :key="index"
                    :class="{ 'flex-row-reverse': isSender(chat) }">
                    <ChatAvatar :src="getUserAvatar(chat)" class="w-8 h-8" />
                    <ChatMessage :variant="isSender(chat) ? 'sender' : 'default'">
                        {{ chat.content }}
                    </ChatMessage>
                </ChatItem>
            </ChatList>
        </ChatContent>
        <ChatForm @submit.prevent="sendMessage" method="POST">
            <Textarea placeholder="Type your message..." v-model="formInput" />
            <Button class="absolute right-[0.5rem] top-[0.7rem]" type="submit">Send
                <Send />
            </Button>
        </ChatForm>
    </Chat>
</template>