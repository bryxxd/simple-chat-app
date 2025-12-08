<script setup>
import { ref, watchEffect } from "vue";
import { Search, Sparkles, LoaderCircle } from "lucide-vue-next";
import { Input } from '@/Components/ui/input';
import { SearchResult } from '@/Components/ui/search';
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const searchInput = ref("");
const searchusers = ref([]);
const isLoading = ref(false);
const users = computed(() => usePage().props.users);

async function fetchUsers(pattern) {
    try {
        isLoading.value = true;
        const response = await fetch(`/api/users/search/${pattern}`);
        if (response.ok) {
            searchusers.value = await response.json();
            isLoading.value = false;
        } else {
            console.error('Error fetching users:', response.statusText);
            isLoading.value = false;
        }
    } catch (error) {
        console.error(error);
        isLoading.value = false;
    }
}

watchEffect(() => {
    if (searchInput.value && searchInput.value.length > 1) {
        fetchUsers(searchInput.value);
    }
});
</script>
<template>
    <div>
        <div class="relative w-full p-6 border-b border-gray-200">
            <Input id="search" type="text" placeholder="Search..." class="pl-10 py-6 w-full" v-model="searchInput" />
            <span class="absolute left-6 inset-y-0 flex items-center justify-center px-2">
                <Search class="size-6 text-muted-foreground" />
            </span>
        </div>
        <div class="p-6">
            <h3 class="inline-flex items-center gap-4 text-lg">
                <Sparkles class="text-yellow-500" />People you may know
            </h3>
            <div class="space-y-2 mt-6">
                <template v-if="isLoading">
                    <LoaderCircle class="animate-spin mx-auto" />
                </template>
                <template v-else>
                    <template v-if="searchInput">
                        <template v-if="searchusers.length === 0">
                            <p class="text-center text-muted-foreground">No users found.</p>
                        </template>
                        <template v-else>
                            <SearchResult v-for="user in searchusers" :key="user.id" :user="user" />
                        </template>
                    </template>
                    <template v-else>
                        <SearchResult v-for="user in users" :key="user.id" :user="user" />
                    </template>
                </template>
            </div>
        </div>
    </div>

</template>
