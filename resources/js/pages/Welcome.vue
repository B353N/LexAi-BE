<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Добре дошли в LexAI" />

    <div class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-indigo-500 selection:text-white">
            <div class="relative w-full max-w-4xl px-6 lg:px-8">
                <header class="flex items-center justify-between py-8">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1.365l-5.73 5.73a1 1 0 00-.306 1.037l.037.062a1 1 0 001.036.706h12a1 1 0 001.036-.706l.037-.062a1 1 0 00-.306-1.037L11 4.365V3a1 1 0 00-1-1zM2.57 12h14.86l-3.59 3.59a1 1 0 01-1.414 0L10 12.586l-2.293 2.293a1 1 0 01-1.414 0L2.57 12z" clip-rule="evenodd" />
                        </svg>
                        <h1 class="ml-3 text-2xl font-bold tracking-wider">LexAI</h1>
                    </div>
                    <nav v-if="canLogin" class="flex items-center space-x-4">
                        <div v-if="$page.props.auth.user" class="flex items-center space-x-4">
                            <Link
                                :href="route('dashboard')"
                                class="rounded-md bg-indigo-500 px-4 py-2 text-white transition duration-300 ease-in-out hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
                            >
                                Табло за управление
                            </Link>
                            <Link
                                :href="route('chat')"
                                class="rounded-md px-4 py-2 text-gray-600 transition duration-300 ease-in-out hover:text-indigo-500 dark:text-gray-400 dark:hover:text-indigo-400"
                            >
                                Чат
                            </Link>
                        </div>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-4 py-2 text-gray-600 transition duration-300 ease-in-out hover:text-indigo-500 dark:text-gray-400 dark:hover:text-indigo-400"
                            >
                                Вход
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md border border-indigo-500 px-4 py-2 text-indigo-500 transition duration-300 ease-in-out hover:bg-indigo-500 hover:text-white"
                            >
                                Регистрация
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-12">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
                        <div class="p-8 sm:p-12">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                                Вашият интелигентен правен асистент
                            </h2>
                            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
                                LexAI е иновативна платформа, която предоставя автоматизирани правни услуги за малки и средни предприятия в България. Спестете време и ресурси, като се доверите на нашия AI асистент за правни консултации, анализ на договори и много други.
                            </p>
                            <div class="mt-8">
                                <Link
                                    :href="route('register')"
                                    class="inline-block rounded-md bg-indigo-500 px-8 py-3 text-lg font-semibold text-white transition duration-300 ease-in-out hover:bg-indigo-600 transform hover:-translate-y-1"
                                >
                                    Започнете безплатно
                                </Link>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-8 mt-12 text-center text-sm text-gray-500 dark:text-gray-400">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }}) &copy; {{ new Date().getFullYear() }} LexAI. Всички права запазени.
                </footer>
            </div>
        </div>
    </div>
</template> 