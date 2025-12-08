<script setup>
import { Skeleton } from "@/Components/ui/skeleton";
import { Chat, ChatContent, ChatDetails } from '@/Components/ui/chat';

</script>
<template>
    <Chat>
        <ChatDetails>
            <div class="flex">
                <Skeleton class="h-12 w-12 rounded-full" />
                <div class="flex flex-col justify-between ml-4">
                    <Skeleton class="h-5 w-[250px]" />
                    <Skeleton class="h-5 w-[150px]" />
                </div>
            </div>
        </ChatDetails>
        <ChatContent>
            <Skeleton class="w-full h-full" />
        </ChatContent>
    </Chat>
</template>
