<script>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { useForm, usePage } from "@inertiajs/vue3";
import { Skeleton, SkeletonChatDetails, SkeletonChatList } from '@/components/ui/skeleton';
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
        Send,
        Skeleton,
        SkeletonChatDetails,
        SkeletonChatList
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
        isLoading: {
            type: Boolean,
            required: false,
            default: false
        },
    },
    inject: ['isOnline'],
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
        window.Echo.private('new-messages.' + this.authUser.id)
            .listen('NewMessageEvent', e => {
                const isMessageForCurrentChat =
                    (e.from_user_id === this.authUser.id && e.to_user_id === this.activeUser?.id) ||
                    (e.from_user_id === this.activeUser?.id && e.to_user_id === this.authUser.id);

                if (isMessageForCurrentChat) {
                    this.chats.messages.push({
                        content: e.content,
                        from_user_id: e.from_user_id,
                        to_user_id: e.to_user_id
                    });
                }
            });
    }
};

</script>
<template>
    <Chat>
        <ChatDetails>
            <SkeletonChatDetails v-if="isLoading" />
            <div class="flex" v-else>
                <ChatAvatar :src="chats?.to_user_details?.avatar" />
                <div class="flex flex-col justify-between ml-4">
                    <ChatName>{{ chats?.to_user_details?.first_name }} {{ chats?.to_user_details?.last_name }}
                    </ChatName>
                    <ChatStatus v-if="isOnline(activeUser.id)" class="text-green-700">Online</ChatStatus>
                </div>
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