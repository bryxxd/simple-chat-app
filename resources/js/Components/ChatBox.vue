<script>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { useForm, usePage } from "@inertiajs/vue3";

export default {
    components: {
        Chat,
        ChatContent,
        ChatDetails,
        ChatAvatar,
        ChatMessage,
        ChatName,
        ChatStatus,
        ChatItem,
        ChatForm,
        ChatList,
        Textarea,
        Button,
        Send
    },
    props: {
        user: {
            type: [Object],
            default: null
        },
        chats: {
            type: Array,
            required: true,
            default: () => []
        },
        to_user_avatar: {
            type: String,
            default: '',
        }
    },
    data() {
        return {
            form: useForm({
                'message': '',
                'to_user_id': ''
            }),
        }
    },
    methods: {
        sendMessage() {
            this.form.post(route('messages.store'), {
                preserveState: true,
                onProgress: (e) => {
                    console.log('Sending message...', e);
                },
                onSuccess: () => {
                    this.chats.push({
                        content: this.form.message,
                        from_user_id: this.authUser.id,
                        to_user_id: this.form.to_user_id,
                        created_at: new Date().toISOString()
                    });
                    this.form.message = '';
                }
            })
        },
        isReversed(chat) {
            return this.authUser && chat.from_user_id === this.authUser.id;
        },
        getUserAvatar(chat) {
            return this.authUser && chat.from_user_id === this.authUser.id
                ? this.authUser.avatar
                : this.to_user_avatar
        },
    },
    computed: {
        authUser() {
            return usePage().props.auth.user;
        }
    },
    watch: {
        user: {
            async handler(newUser) {
                if (newUser) {
                    this.form.to_user_id = newUser.id;
                }
            },
            immediate: true
        },
    },
}

</script>
<template>
    <Chat>
        <ChatDetails>
            <ChatAvatar :src="to_user_avatar" />
            <div class="flex flex-col justify-between ml-4">
                <ChatName>{{ user.first_name }} {{ user.last_name }}</ChatName>
                <!-- <ChatStatus v-if="activeChat.participant.isOnline" class="text-green-700">Online</ChatStatus> -->
            </div>
        </ChatDetails>
        <ChatContent ref="chatContent">
            <ChatList>
                <ChatItem v-for="(chat, index) in chats" :key="index" :class="{ 'flex-row-reverse': isReversed(chat) }">
                    <ChatAvatar :src="getUserAvatar(chat)" class="w-8 h-8" />
                    <ChatMessage :class="{ 'bg-primary text-primary-foreground': isReversed(chat) }">
                        {{ chat.content }}
                    </ChatMessage>
                </ChatItem>
            </ChatList>
        </ChatContent>
        <ChatForm @submit.prevent="sendMessage" method="POST">
            <Textarea placeholder="Type your message..." v-model="form.message" />
            <Button class="absolute right-[0.5rem] top-[0.7rem]" type="submit">Send
                <Send />
            </Button>
        </ChatForm>
    </Chat>
</template>