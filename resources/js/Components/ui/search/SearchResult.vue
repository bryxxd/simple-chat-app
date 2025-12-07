<script setup>
import { useChatStore } from "@/stores/chatStore";
import { Avatar, AvatarImage } from "@/components/ui/avatar";


const chatStore = useChatStore();
const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

</script>
<template>
    <div
        class="flex items-center gap-4 p-3 bg-background rounded-lg border border-gray-200 hover:shadow-sm transition-all hover:border-gray-300">
        <Avatar>
            <AvatarImage :src="props.user.avatar" alt="" />
        </Avatar>
        <div class="flex-1">
            <h4 class="font-medium text-background-900">{{ props.user?.first_name }} {{ props.user?.last_name }}
            </h4>
        </div><button @click="chatStore.updateSelectedUser(props.user.id)"
            class="bg-primary hover:bg-primary/90 text-primary-foreground px-4 py-2 rounded-full text-sm transition-colors">Message</button>
    </div>
</template>