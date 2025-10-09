<script setup>
import { usePage } from "@inertiajs/vue3";
import { computed, inject } from "vue";
import { Search } from "lucide-vue-next";
import { Avatar, AvatarImage } from "@/components/ui/avatar";
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxList,
} from "@/components/ui/combobox";
import { useChatStore } from "@/stores/chatStore";
const chatStore = useChatStore();

// Computed properties
const users = computed(() => usePage().props.users);

// Methods
const getUserAvatar = (user) => user?.avatar || '/images/profile-placeholder.jpg';
</script>

<template>
    <Combobox by="name" :items="users" class="w-full">
        <ComboboxAnchor>
            <div class="relative w-full max-w-sm items-center">
                <ComboboxInput class="pl-9" :display-value="(val) => val?.name ?? ''" placeholder="Search User..." />
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                    <Search class="size-4 text-muted-foreground" />
                </span>
            </div>
        </ComboboxAnchor>

        <ComboboxList class="w-[256px] md:w-[317px]">
            <ComboboxEmpty> No user found. </ComboboxEmpty>

            <ComboboxGroup>
                <ComboboxItem v-for="user in users" :key="user.id" :value="user" class="justify-start"
                    @click="chatStore.updateSelectedUser(user.id)">
                    <Avatar>
                        <AvatarImage :src="getUserAvatar(user)" alt="" />
                    </Avatar>
                    {{ user.first_name }} {{ user.last_name }}
                </ComboboxItem>
            </ComboboxGroup>
        </ComboboxList>
    </Combobox>
</template>
