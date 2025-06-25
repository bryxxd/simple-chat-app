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
    data() {
        return {
            avatar: '/images/shadcn.jpg',
            isCurrentUser: true
        }
    }
}

</script>
<template>
    <Chat>
        <ChatDetails>
            <ChatAvatar :src="avatar" />
            <div class="flex flex-col justify-center ml-4">
                <ChatName>ShadCN</ChatName>
                <ChatStatus>Online</ChatStatus>
            </div>
        </ChatDetails>

        <ChatContent>
            <ChatList>
                <ChatItem>
                    <ChatAvatar :src="avatar" class="w-8 h-8 mr-2" />
                    <ChatMessage variant="sender">
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book. It has survived not only five centuries,
                        but also the leap into electronic typesetting, remaining essentially unchanged. It was
                        popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                        and more recently with desktop publishing software like Aldus PageMaker including versions of
                        Lorem Ipsum.
                    </ChatMessage>
                </ChatItem>
                <ChatItem>
                    <ChatAvatar :src="avatar" class="w-8 h-8 mr-2" />
                    <ChatMessage>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book. It has survived not only five centuries,
                        but also the leap into electronic typesetting, remaining essentially unchanged. It was
                        popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                        and more recently with desktop publishing software like Aldus PageMaker including versions of
                        Lorem Ipsum.
                    </ChatMessage>
                </ChatItem>
                <ChatItem>
                    <ChatAvatar :src="avatar" class="w-8 h-8 mr-2" />
                    <ChatMessage>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book. It has survived not only five centuries,
                        but also the leap into electronic typesetting, remaining essentially unchanged. It was
                        popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                        and more recently with desktop publishing software like Aldus PageMaker including versions of
                        Lorem Ipsum.
                    </ChatMessage>
                </ChatItem>
                <ChatItem>
                    <ChatAvatar :src="avatar" class="w-8 h-8 mr-2" />
                    <ChatMessage>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                        the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley
                        of type and scrambled it to make a type specimen book. It has survived not only five centuries,
                        but also the leap into electronic typesetting, remaining essentially unchanged. It was
                        popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
                        and more recently with desktop publishing software like Aldus PageMaker including versions of
                        Lorem Ipsum.
                    </ChatMessage>
                </ChatItem>
            </ChatList>
        </ChatContent>

        <ChatForm>
            <Textarea placeholder="Type your message..." />
            <Button class="absolute right-[0.5rem] top-[0.7rem]">Send
                <Send />
            </Button>
        </ChatForm>
    </Chat>
</template>