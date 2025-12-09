<script setup>
import { ref, watchEffect } from "vue";
import { computed } from "vue";
import { Search } from "lucide-vue-next";
import { Avatar, AvatarImage } from "@/Components/ui/avatar";
import { cn } from "@/lib/utils";
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxList,
} from "@/Components/ui/combobox";
import { useSidebar } from '@/Components/ui/sidebar/utils';
import { useChatStore } from "@/stores/chatStore";

const chatStore = useChatStore();
const searchusers = ref([]);
const searchInput = ref("");
const props = defineProps({
    class: { type: null, required: false },
});
const { setOpenMobile } = useSidebar();

function updateSelectedUser(userId) {
    chatStore.updateSelectedUser(userId);
    setOpenMobile(false);
}

async function fetchUsers(pattern) {
    try {
        const response = await fetch(`/api/users/search/${pattern}`);
        if (response.ok) {
            searchusers.value = await response.json();
        } else {
            console.error('Error fetching users:', response.statusText);
        }
    } catch (error) {
        console.error('Network error:', error);
    }
}

watchEffect(() => {
    if (searchInput.value && searchInput.value.length > 1) {
        fetchUsers(searchInput.value);
    } else {
        searchusers.value = [];
    }
});

// Computed properties
const users = computed(() => searchusers.value);

// Methods
const getUserAvatar = (user) => user?.avatar || '/images/profile-placeholder.jpg';
</script>

<template>
    <Combobox by="name" :items="users" class="w-full">
        <ComboboxAnchor>
            <div :class="cn('relative w-full items-center', props.class)" >
                <ComboboxInput class="pl-9" :display-value="(val) => val?.name ?? ''" placeholder="Search User..." v-model="searchInput"/>
                <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                    <Search class="size-4 text-muted-foreground" />
                </span>
            </div>
        </ComboboxAnchor>

        <ComboboxList class="w-[256px] md:w-[317px]">
            <ComboboxEmpty> No user found. </ComboboxEmpty>

            <ComboboxGroup>
                <ComboboxItem v-for="user in users" :key="user.id" :value="user" class="justify-start"
                    @click="updateSelectedUser(user.id)">
                    <Avatar>
                        <AvatarImage :src="getUserAvatar(user)" alt="" />
                    </Avatar>
                    {{ user.first_name }} {{ user.last_name }}
                </ComboboxItem>
            </ComboboxGroup>
        </ComboboxList>
    </Combobox>
</template>
