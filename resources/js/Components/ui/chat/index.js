import { cva } from "class-variance-authority";

export { default as Chat } from "./Chat.vue";
export { default as ChatAvatar } from "./ChatAvatar.vue";
export { default as ChatContent } from "./ChatContent.vue";
export { default as ChatDetails } from "./ChatDetails.vue";
export { default as ChatItem } from "./ChatItem.vue";
export { default as ChatName } from "./ChatName.vue";
export { default as ChatStatus } from "./ChatStatus.vue";
export { default as ChatForm } from "./ChatForm.vue";
export { default as ChatMessage } from "./ChatMessage.vue";
export { default as ChatList } from "./ChatList.vue";

export const chatVariants = cva(
    "bg-muted p-2 rounded-lg max-w-lg",
    {
        variants: {
            variant: {
                default: "bg-secondary text-secondary-foreground ",
                sender : "bg-primary text-primary-foreground"
            },
        },
    }
);