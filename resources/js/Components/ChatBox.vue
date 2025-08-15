<script setup>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { useForm, usePage } from "@inertiajs/vue3";
import UserActiveStatus from './UserActiveStatus.vue';
import { SkeletonChatDetails, SkeletonChatList } from '@/components/ui/skeleton';
import { inject, onMounted, watch } from 'vue';

// Props 
const props = defineProps({
    chats: {
        type: Object,
        required: true,
        default: () => ({})
    },
    activeUser: {
        type: Object,
        required: true,
        default: () => ({})
    },
    isLoading: {
        type: Boolean,
        required: false,
        default: false
    },
});

// Form
const form = useForm({
    message: '',
    to_user_id: props.activeUser?.id || ''
});

// Inject
const isOnline = inject('isOnline');

// Watch for activeUser changes
watch(() => props.activeUser, (newActiveUser) => {
    if (newActiveUser?.id) {
        form.to_user_id = newActiveUser.id;
    }
}, { immediate: true });

// Methods
function sendMessage() {
    if (!form.to_user_id) {
        console.error('No active user selected');
        return;
    }
    
    form.post(route('messages.store'), {
        preserveState: true,
        onSuccess: () => {
            // Message will be added via Echo listener, just clear the form
            form.message = '';
        },
        onError: (errors) => {
            console.log('Error sending message:', errors);
        }
    });
}

function isSender(chat) {
    return authUser && chat.from_user_id === authUser.id;
}

function getUserAvatar(chat) {
    return authUser && chat.from_user_id === authUser.id
        ? authUser.avatar
        : props.chats.to_user_details.avatar;
}

// Computed
const authUser = usePage().props.auth.user;

// Mounted
onMounted(() => {
    window.Echo.private('new-messages.' + authUser.id)
        .listen('NewMessageEvent', e => {
            const isMessageForCurrentChat =
                (e.from_user_id === authUser.id && e.to_user_id === props.activeUser?.id) ||
                (e.from_user_id === props.activeUser?.id && e.to_user_id === authUser.id);

            if (isMessageForCurrentChat) {
                props.chats.messages.push({
                    content: e.content,
                    from_user_id: e.from_user_id,
                    to_user_id: e.to_user_id
                });
            }
        });
});

</script>
<template>
    <Chat>
        <ChatDetails>
            <SkeletonChatDetails v-if="isLoading" />
            <div class="flex" v-else-if="activeUser">
                <ChatAvatar :src="chats?.to_user_details?.avatar" />
                <div class="flex flex-col justify-between ml-4">
                    <ChatName>{{ chats?.to_user_details?.first_name }} {{ chats?.to_user_details?.last_name }}
                    </ChatName>
                    <ChatStatus v-if="isOnline(activeUser.id)" class="text-green-700">Online</ChatStatus>
                    <UserActiveStatus v-else :id="activeUser.id" />
                </div>
            </div>
            <div v-else class="flex items-center justify-center text-gray-500">
                <p>No active user selected</p>
            </div>
        </ChatDetails>
        <ChatContent ref="chatContent">
            <ChatList ref="chatList">
                <SkeletonChatList v-if="isLoading" />
                <ChatItem v-else v-for="(chat, index) in chats.messages" :key="index"
                    :class="{ 'flex-row-reverse': isSender(chat) }">
                    <ChatAvatar :src="getUserAvatar(chat)" class="w-8 h-8" />
                    <ChatMessage :variant="isSender(chat) ? 'sender' : 'default'">
                        {{ chat.content }}
                    </ChatMessage>
                </ChatItem>
            </ChatList>
        </ChatContent>
        <ChatForm @submit.prevent="sendMessage" method="POST" v-if="!isLoading">
            <Textarea placeholder="Type your message..." v-model="form.message" />
            <Button class="absolute right-[0.5rem] top-[0.7rem]" type="submit">Send
                <Send />
            </Button>
        </ChatForm>
    </Chat>
</template>