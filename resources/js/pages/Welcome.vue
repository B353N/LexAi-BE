<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Добре дошли в LexAI" />
    <div class="flex min-h-screen flex-col items-center bg-gray-100 p-6 dark:bg-gray-900">
        <header class="w-full max-w-5xl">
            <nav class="flex items-center justify-end gap-4 p-4 text-sm">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                >
                    Табло за управление
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        Вход
                    </Link>
                    <Link
                        :href="route('register')"
                        class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        Регистрация
                    </Link>
                </template>
            </nav>
        </header>

        <main class="flex flex-1 flex-col items-center justify-center text-center">
            <div class="w-full max-w-3xl px-6">
                <h1 class="text-4xl font-bold text-gray-800 dark:text-white sm:text-5xl md:text-6xl">
                    Добре дошли в LexAI
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 md:text-xl">
                    Вашият интелигентен правен асистент. Автоматизирани правни съвети и генериране на документи за малки и средни предприятия в България.
                </p>
                <div class="mt-8">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('register')"
                        class="rounded-md bg-blue-600 px-8 py-3 text-lg font-semibold text-white shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Започнете сега
                    </Link>
                     <Link
                        v-else
                        :href="route('dashboard')"
                        class="rounded-md bg-blue-600 px-8 py-3 text-lg font-semibold text-white shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Към таблото за управление
                    </Link>
                </div>
            </div>
        </main>

        <footer class="w-full max-w-5xl py-4 text-center text-sm text-gray-500 dark:text-gray-400">
            © {{ new Date().getFullYear() }} LexAI. Всички права запазени.
        </footer>
    </div>
</template>
