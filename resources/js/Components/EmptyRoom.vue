<script setup>
import { Plus, MessageCircle } from "lucide-vue-next"; 
import { Button } from '@/components/ui/button';
import { inject } from "vue";

const { switchNoInteractionTab } = inject('noInteractionTabs');
</script>
<template>
    <div class="text-center">
        <MessageCircle class="mx-auto mb-3 block w-[6rem] h-[6rem]" />
        <h3 class="font-semibold text-xl mb-4">No Conversation yet</h3>
        <p class="text-base">Start chatting with friends and family</p>
        <Button @click="switchNoInteractionTab('StartConversation')" class="mt-4">
            <Plus />
            <span>Start a conversation</span>
        </Button>
    </div>

</template>