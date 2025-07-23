<script setup lang="ts">
import { usePage } from "@inertiajs/vue3"; 
import { computed } from "vue";
import { Search } from "lucide-vue-next";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import {
    Combobox,
    ComboboxAnchor,
    ComboboxEmpty,
    ComboboxGroup,
    ComboboxInput,
    ComboboxItem,
    ComboboxItemIndicator,
    ComboboxList,
} from "@/components/ui/combobox";

const page = usePage();

const users = computed(() => {
    return page.props.users.users.map(user => ({
        name: user.first_name + ' ' + user.last_name,
        img: user.avatar,
    }));
});

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
                <ComboboxItem v-for="user in users" :key="user.name" :value="user" class="justify-start">
                    <Avatar>
                        <AvatarImage :src="user.img" :alt="user.name" />
                        <AvatarFallback>CN</AvatarFallback>
                    </Avatar>
                    {{ user.name }}
                </ComboboxItem>
            </ComboboxGroup>
        </ComboboxList>
    </Combobox>
</template>
