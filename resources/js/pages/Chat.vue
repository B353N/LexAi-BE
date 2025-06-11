<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    chatSessions: Array,
    chatSession: Object,
});

const messages = computed(() => props.chatSession?.messages ?? []);

const form = useForm({
    message: '',
});

const submit = () => {
    if (!props.chatSession) return;
    form.put(route('chat.update', props.chatSession.id), {
        onSuccess: () => form.reset('message'),
    });
};
</script>

<template>
    <Head title="Чат" />
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Чат с LexAI</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex h-[600px]">
                        <!-- Sidebar -->
                        <div class="w-1/4 bg-gray-100 dark:bg-gray-700 p-4 border-r border-gray-200 dark:border-gray-600">
                            <Link :href="route('chat.store')" method="post" as="button" class="w-full px-4 py-2 bg-indigo-500 text-white rounded-md hover:bg-indigo-600 mb-4">
                                Нова тема
                            </Link>
                            <div class="space-y-2">
                                <Link v-for="session in chatSessions" :key="session.id" :href="route('chat.show', session.id)" class="block px-4 py-2 rounded-md" :class="{ 'bg-indigo-200 dark:bg-indigo-800': chatSession?.id === session.id }">
                                    {{ session.title }}
                                </Link>
                            </div>
                        </div>

                        <!-- Main Chat Area -->
                        <div class="flex-1 flex flex-col">
                            <div class="flex-grow overflow-y-auto p-4 space-y-4">
                                <div v-for="message in messages" :key="message.id" class="flex" :class="message.sender === 'user' ? 'justify-end' : 'justify-start'">
                                    <div class="rounded-lg px-4 py-2" :class="message.sender === 'user' ? 'bg-indigo-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200'">
                                        {{ message.message }}
                                    </div>
                                </div>
                                <div v-if="!chatSession" class="text-center text-gray-500">
                                    Изберете тема или започнете нова.
                                </div>
                            </div>
                            <form v-if="chatSession" @submit.prevent="submit" class="p-4 border-t border-gray-200 dark:border-gray-700 flex">
                                <input v-model="form.message" type="text" placeholder="Напишете вашето съобщение..." class="flex-grow rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-indigo-500 text-white rounded-r-md hover:bg-indigo-600 disabled:bg-indigo-300">
                                    Изпрати
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 