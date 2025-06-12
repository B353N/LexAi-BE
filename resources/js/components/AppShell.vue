<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import { usePage } from '@inertiajs/vue3';

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = usePage().props.sidebarOpen;
</script>

<template>
    <div v-if="variant === 'header'" class="flex h-screen w-full flex-col">
        <slot />
    </div>
    <div v-else class="flex h-screen w-full">
        <SidebarProvider :default-open="isOpen" class="flex h-full w-full">
            <slot />
        </SidebarProvider>
    </div>
</template>
