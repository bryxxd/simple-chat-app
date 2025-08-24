<script setup>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { usePage } from "@inertiajs/vue3";
import UserActiveStatus from './UserActiveStatus.vue';
import { computed, inject, watch } from 'vue';
import axios from 'axios';

// Props 
const props = defineProps({
    chats: {
        type: Object,
        required: false,
        default: () => ({})
    },
    activeUser: {
        type: Object,
        required: false,
        default: () => ({})
    },
    isLoading: {
        type: Boolean,
        required: false,
        default: false
    },
});

// Form
const form = {
    to_user_id: props.activeUser?.id || ''
}

// V-model
const formInput = defineModel();

// Inject
const isOnline = inject('isOnline');
const moveUserToTop = inject('moveUserToTop');

// Computed
const authUser = computed(() => usePage().props.auth.user || {});

// Methods
async function sendMessage() {
    if (!form.to_user_id) {
        console.error('No active user selected');
        return;
    }

    // Store the content before clearing it
    const messageContent = formInput.value;

    try {
        await axios.post("/api/send-message", {
            message: messageContent,
            to_user_id: form.to_user_id
        });
    } catch (err) {
        console.log(err);
    } finally {
        formInput.value = '';
    }

    // Use the stored content
    moveUserToTop({
        targetUserId: form.to_user_id,
        content: messageContent
    });
}

function isSender(chat) {
    return authUser.value && chat.from_user_id === authUser.value.id;
}

function getUserAvatar(chat) {
    return authUser.value && chat.from_user_id === authUser.value.id
        ? authUser.value.avatar
        : props.chats.participant.avatar;
}

// Watch for activeUser changes
watch(() => props.activeUser, (newActiveUser) => {
    if (newActiveUser?.id) {
        form.to_user_id = newActiveUser.id;
    }
}, { immediate: true });
</script>
<template>
    <Chat>
        <ChatDetails>
            <div class="flex">
                <ChatAvatar :src="chats?.participant?.avatar" />
                <div class="flex flex-col justify-between ml-4">
                    <ChatName>{{ chats?.participant?.first_name }} {{ chats?.participant?.last_name }}
                    </ChatName>
                    <ChatStatus v-if="isOnline(activeUser?.id)" class="text-green-700">Online</ChatStatus>
                    <UserActiveStatus v-else :id="activeUser?.id" />
                </div>
            </div>
        </ChatDetails>
        <ChatContent ref="chatContent">
            <ChatList ref="chatList">
                <ChatItem v-for="(chat, index) in chats.messages" :key="index"
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