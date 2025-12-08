<script setup>
import AuthLayout from "@/Layouts/AuthLayout.vue";
import AppearanceTabs from "@/Components/AppearanceTabs.vue";
import Settings from "@/Layouts/settings/Layout.vue";
import { Head } from '@inertiajs/vue3';
import {
    BreadcrumbItem,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/Components/ui/breadcrumb";
</script>
<template>
    <Head title="Appearance" />
    <AuthLayout>
        <template v-slot:currentPage>
            <BreadcrumbSeparator/>
            <BreadcrumbItem>
                <BreadcrumbPage>Appearance</BreadcrumbPage>
            </BreadcrumbItem>
        </template>
        <Settings>
            <div>
                <h3 class="text-lg font-medium"> Apperance </h3>
                <p class="text-sm text-muted-foreground">Manage the appearance of your application.</p>
            </div>
            <div data-orientation="horizontal" role="none" class="shrink-0 bg-border h-px w-full"></div>
            <AppearanceTabs />
        </Settings>
    </AuthLayout>
</template>
