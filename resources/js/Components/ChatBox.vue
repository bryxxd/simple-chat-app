<script>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { useEcho, useEchoPublic } from '@laravel/echo-vue';
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
                onSuccess: () => {
                    // Message will be added via Echo listener, just clear the form
                    this.form.message = '';
                },
                onError: (errors) => {
                    console.log('Error sending message:', errors);
                }
            });
        },
        isSender(chat) {
            return this.authUser && chat.from_user_id === this.authUser.id;
        },
        getUserAvatar(chat) {
            return this.authUser && chat.from_user_id === this.authUser.id
                ? this.authUser.avatar
                : this.chats.to_user_details.avatar;
        },
    },
    computed: {
        authUser() {
            return usePage().props.auth.user;
        }
    },
    watch: {
        activeUser: {
            async handler(newUser) {
                if (newUser) {
                    this.form.to_user_id = newUser.id;
                }
            },
            immediate: true
        },
    },
    mounted() {
        const { listen } = useEcho(
            'new-messages.' + this.authUser.id,
            'NewMessageEvent',
            (e) => {
                // Check if the message is relevant to the current chat
                const isMessageForCurrentChat =
                    (e.from_user_id === this.authUser.id && e.to_user_id === this.activeUser?.id) ||
                    (e.from_user_id === this.activeUser?.id && e.to_user_id === this.authUser.id);

                if (isMessageForCurrentChat) {
                    // Append the new message to the chat
                    this.chats.messages.push({
                        content: e.content,
                        from_user_id: e.from_user_id,
                        to_user_id: e.to_user_id
                    });
                }
            });
        listen()
    }
};

</script>
<template>
    <Chat>
        <ChatDetails>
            <ChatAvatar :src="chats?.to_user_details?.avatar" />
            <div class="flex flex-col justify-between ml-4">
                <ChatName>{{ chats?.to_user_details?.first_name }} {{ chats?.to_user_details?.last_name }}</ChatName>
                <!-- <ChatStatus v-if="activeChat.participant.isOnline" class="text-green-700">Online</ChatStatus> -->
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
            <Textarea placeholder="Type your message..." v-model="form.message" />
            <Button class="absolute right-[0.5rem] top-[0.7rem]" type="submit">Send
                <Send />
            </Button>
        </ChatForm>
    </Chat>
</template>