<script>
export default {
    name: 'Room',
    props: {
        chat: {
            type: Object,
            required: true
        }
    },
    emits: ['click'],
    methods: {
        handleClick() {
            this.$emit('click');
        }
    }
}
</script>
<template>
    <div class="flex items-center gap-2 whitespace-nowrap p-4 text-sm leading-tight last:border-b-0 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer"
        @click="handleClick">
        <slot>
            <img class="rounded-full w-12 h-12" :src="chat.participant.avatar" alt="">
            <div class="flex flex-col gap-3 justify-between w-48 overflow-hidden">
                <h3 class="text-ellipsis overflow-hidden whitespace-nowrap">{{ chat.participant.name }}</h3>
                <p class="text-ellipsis overflow-hidden whitespace-nowrap">{{ chat.lastMessage.content }}</p>
            </div>
        </slot>
    </div>
</template>