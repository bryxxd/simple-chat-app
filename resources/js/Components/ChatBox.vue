<script>
import { Chat, ChatContent, ChatDetails, ChatAvatar, ChatMessage, ChatName, ChatItem, ChatList, ChatStatus, ChatForm } from '@/components/ui/chat';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';
import { Send } from "lucide-vue-next";
import { useForm } from "@inertiajs/vue3";

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
            type: [Object, null],
            required: true,
            default: null
        },
    },
    emits: ['update:modelValue'],
    data() {
        return {
            avatar: '/images/shadcn.jpg',
            isSender : true,
            form : useForm({
                'message' : '',
                'to_user_id' : ''
            }),
            messages : [
                {
                    id: 1,
                    content: 'Hello!',
                    senderId: 1
                },
                {
                    id: 2,
                    content: 'Hi there!',
                    senderId: 2
                }
            ]
        }
    },
    methods: {
        sendMessage() {
           this.form.post(route('messages.store'), {
                onBefore: () => {
                    console.log('To user ID: ' , this.form.to_user_id);
                },
                onSuccess: () => {
                    this.form.message = ''
                }
           })
        }
    }, 
    watch: {
        user(newUser) {
            if (newUser) {
                this.form.to_user_id = newUser.id;
            }
        }
    }
}

</script>
<template>
    <Chat>
        <pre>
            {{ user.id }}
        </pre>
        <ChatDetails>
            <ChatAvatar :src="user.avatar" />
            <div class="flex flex-col justify-between ml-4">
                <ChatName>{{ user.first_name }} {{ user.last_name }}</ChatName>
                <!-- <ChatStatus v-if="activeChat.participant.isOnline" class="text-green-700">Online</ChatStatus> -->
            </div>
        </ChatDetails>

        <ChatContent ref="chatContent">
            <ChatList>
                <ChatItem v-for="(message, index) in messages" :key="index" :class="{'flex-row-reverse': message.senderId === 1}">
                    <ChatAvatar :src="avatar" class="w-8 h-8" />
                    <ChatMessage :class="{ 'bg-primary text-primary-foreground' : message.senderId === 1}">{{ message.content }}</ChatMessage>
                </ChatItem>
            </ChatList>
        </ChatContent>
        <ChatForm @submit.prevent="sendMessage" method="POST">
            <Textarea placeholder="Type your message..." v-model="form.message" />
            <Button class="absolute right-[0.5rem] top-[0.7rem]" type="submit">Send<Send /></Button>
        </ChatForm>
    </Chat>
</template>