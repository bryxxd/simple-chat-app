<script>
export default {
    name: 'Room',
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    computed: {
        userAvatarSrc() {
            return this.user?.avatar || '/images/profile-placeholder.jpg';
        }
    },
    inject: ['isOnline']
}
</script>
<template>
    <div class="flex items-center gap-2 whitespace-nowrap p-4 text-sm leading-tight last:border-b-0 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground cursor-pointer">
        <slot>
            <div class="relative">
                <img class="rounded-full w-12 h-12" :src="userAvatarSrc" alt="">
                <span v-if="isOnline(user?.id)" class="w-2 h-2 bg-green-700 inline-block rounded-full absolute right-2 bottom-0"></span>
            </div>
            <div class="flex flex-col gap-3 justify-between w-48 overflow-hidden">
                <h3 class="text-ellipsis overflow-hidden whitespace-nowrap">{{ user?.first_name }} {{ user?.last_name }}</h3>
                <!-- <p class="text-ellipsis overflow-hidden whitespace-nowrap">{{ user.lastMessage.content }}</p> -->
            </div>
        </slot>
    </div>
</template>