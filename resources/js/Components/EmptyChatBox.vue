<script setup>
import { MessageCircle, Users,ArrowRight } from "lucide-vue-next";
import { inject } from "vue";
const { switchNoInteractionTab } = inject('noInteractionTabs');
</script>
<template>
    <div class="text-center absolute left-0 right-0 top-2/4 -translate-y-1/2 max-w-md mx-auto px-4">
        <MessageCircle class="mx-auto mb-5 block w-[6rem] md:w-[10rem] h-[6rem] md:h-[10rem]" />
        <h2 class="font-semibold text-2xl mb-4">Welcome to ChatUs!</h2>
        <p class="text-base">Connect with friends, family, and colleagues. Start meaningful conversations that matter.
        </p>

        <div class="grid gap-4 mb-8 mt-8">
            <button
                @click="switchNoInteractionTab('StartConversation')"
                class="flex items-center p-4 bg-background rounded-xl border border-gray-200 hover:shadow-md transition-all hover:border-gray-300 text-left group relative">
                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-background mr-4 group-hover:scale-110 transition-transform">
                     <Users />
                </div>
                <div>
                    <h3 class="font-medium text-background-900">Find Friends</h3>
                    <p class="text-background-500 text-sm">Discover people you know</p>
                </div>
                <ArrowRight class="text-background-400 transition-transform  group-hover:translate-x-2 absolute right-10" />
            </button>
        </div>
    </div>
</template>