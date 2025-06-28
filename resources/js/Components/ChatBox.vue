<script>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";

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
        activeChat: {
            type: [Object, null],
            required: true,
            default: null
        }
    },
    emits: ['send-message'],
    data() {
        return {
            avatar: '/images/shadcn.jpg',
            isSender : true,
            messageInput : '',
        }
    },
    methods : {
        // handleSendMessage() {
        //     this.$emit('handle-send-message', 1 , this.messageInput);
        //     this.messageInput = '';
        //     this.$nextTick(() => {
        //         const chatContent = this.$refs.chatContent;
        //         if (chatContent && chatContent.$el) {
        //             chatContent.$el.scrollTop = chatContent.$el.scrollHeight;
        //         } else if (chatContent) {
        //             chatContent.scrollTop = chatContent.scrollHeight;
        //         }
        //     });
        // }
    },
    mounted() {
        // this.$nextTick(() => {
        //     const chatContent = this.$refs.chatContent;
        //     if (chatContent && chatContent.$el) {
        //         chatContent.$el.scrollTop = chatContent.$el.scrollHeight;
        //     } else if (chatContent) {
        //         chatContent.scrollTop = chatContent.scrollHeight;
        //     }
        // });
    }
}

</script>
<template>
    <Chat>
        <ChatDetails>
            <ChatAvatar :src="avatar" />
            <div class="flex flex-col justify-between ml-4">
                <ChatName>{{ activeChat.participant.name }}</ChatName>
                <ChatStatus v-if="activeChat.participant.isOnline" class="text-green-700">Online</ChatStatus>
            </div>
        </ChatDetails>

        <ChatContent ref="chatContent">
            <ChatList>
                <ChatItem v-for="(message, index) in activeChat.messages" :key="index" :class="{'flex-row-reverse': message.senderId === 1}">
                    <ChatAvatar :src="avatar" class="w-8 h-8" />
                    <ChatMessage :class="{ 'bg-primary text-primary-foreground' : message.senderId === 1}">{{ message.content }}</ChatMessage>
                </ChatItem>
            </ChatList>
        </ChatContent>
        <ChatForm>
            <Textarea placeholder="Type your message..." v-model="messageInput"/>
            <Button class="absolute right-[0.5rem] top-[0.7rem]" @click.prevent="$emit('send-message', activeChat.id, messageInput)">Send<Send /></Button>
        </ChatForm>
    </Chat>
</template>