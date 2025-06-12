<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref, watch, nextTick } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { ScrollArea } from '@/components/ui/scroll-area';
import { PlusCircle, Send } from 'lucide-vue-next';

const props = defineProps({
    chatSessions: Array,
    chatSession: Object,
});

const messages = computed(() => props.chatSession?.messages ?? []);
const messageContainer = ref(null);

const form = useForm({
    message: '',
});

const scrollToBottom = (behavior = 'auto') => {
    nextTick(() => {
        const container = messageContainer.value?.$el?.querySelector('[data-radix-scroll-area-viewport]');
        if (container) {
            container.scrollTo({ top: container.scrollHeight, behavior });
        }
    });
};

watch(messages, () => {
    scrollToBottom('smooth');
}, { deep: true, immediate: true });

const submit = () => {
    if (!props.chatSession) return;
    form.put(route('chat.update', props.chatSession.id), {
        onSuccess: () => {
            form.reset('message');
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Чат" />
    <AppLayout>
        <div class="h-full flex">
            <!-- Sidebar with Chat Sessions -->
            <div class="w-1/4 bg-gray-50 dark:bg-gray-900/50 p-4 border-r border-gray-200 dark:border-gray-800 flex flex-col">
                <Link :href="route('chat.store')" method="post" as="button" class="flex items-center justify-center w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mb-6 transition-colors font-semibold shadow-sm">
                    <PlusCircle class="h-5 w-5 mr-2" />
                    Нова тема
                </Link>
                <ScrollArea class="flex-grow">
                    <div class="space-y-2 pr-4">
                        <Link v-for="session in chatSessions" :key="session.id" :href="route('chat.show', session.id)" class="block px-4 py-3 rounded-lg text-sm font-medium transition-colors" :class="{ 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300': chatSession?.id === session.id, 'hover:bg-gray-100 dark:hover:bg-gray-800/50': chatSession?.id !== session.id }">
                            <p class="truncate">{{ session.title }}</p>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ new Date(session.updated_at).toLocaleString() }}</span>
                        </Link>
                    </div>
                </ScrollArea>
            </div>

            <!-- Main Chat Area -->
            <div class="flex-1 flex flex-col min-h-0">
                <Card class="flex-grow flex flex-col border-0 rounded-none">
                    <CardHeader v-if="chatSession" class="border-b dark:border-gray-800">
                        <CardTitle>{{ chatSession.title }}</CardTitle>
                    </CardHeader>

                    <CardContent class="flex-grow p-0">
                        <ScrollArea ref="messageContainer" class="h-full">
                            <div class="p-6 space-y-6">
                                <div v-for="message in messages" :key="message.id" class="flex" :class="message.sender === 'user' ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-xl rounded-xl px-4 py-3" :class="message.sender === 'user' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-200'">
                                        <p class="text-sm whitespace-pre-wrap">{{ message.message }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-if="!chatSession" class="flex items-center justify-center h-full">
                                <div class="text-center text-gray-500">
                                    <h3 class="text-lg font-semibold">Добре дошли в LexAI Чат</h3>
                                    <p>Изберете тема отляво или започнете нова.</p>
                                </div>
                            </div>
                        </ScrollArea>
                    </CardContent>

                    <CardFooter v-if="chatSession" class="p-4 border-t dark:border-gray-800">
                        <form @submit.prevent="submit" class="w-full flex items-center space-x-2">
                            <Input v-model="form.message" type="text" placeholder="Напишете вашето съобщение..." class="flex-grow" autocomplete="off" />
                            <Button type="submit" :disabled="form.processing">
                                <Send class="h-4 w-4" />
                                <span class="sr-only">Изпрати</span>
                            </Button>
                        </form>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template> 