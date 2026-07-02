<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
</script>

<template>
    <Head title="ShortLink — Сервис сокращения ссылок">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#EDEDEC]">
        <header class="mb-6 w-full max-w-4xl text-sm lg:mb-0">
            <nav class="flex items-center justify-between py-4">
                <span class="text-lg font-semibold tracking-tight">ShortLink</span>
                <div class="flex items-center gap-3">
                    <template v-if="$page.props.auth.user">
                        <Link
                            :href="dashboard()"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal hover:border-[#19140035] dark:hover:border-[#3E3E3A]"
                        >
                            Личный кабинет
                        </Link>
                    </template>
                    <template v-else>
                        <a
                            href="/admin/login"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal hover:border-[#19140035] dark:hover:border-[#3E3E3A]"
                        >
                            Войти
                        </a>
                        <a
                            href="/admin/register"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal hover:border-[#1915014a] dark:border-[#3E3E3A] dark:hover:border-[#62605b]"
                        >
                            Регистрация
                        </a>
                    </template>
                </div>
            </nav>
        </header>

        <main class="flex flex-1 flex-col items-center justify-center text-center">
            <div class="mb-8 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-600 text-white">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>

            <h1 class="mb-4 text-4xl font-bold tracking-tight lg:text-5xl">
                Сервис сокращения ссылок
            </h1>

            <p class="mb-8 max-w-xl text-lg text-[#706f6c] dark:text-[#A1A09A]">
                Создавайте короткие ссылки за секунду. Отслеживайте клики, просматривайте
                статистику по IP и датам переходов. Весь контроль — в вашем личном кабинете.
            </p>

            <div class="flex flex-col gap-3 sm:flex-row">
                <a
                    v-if="!$page.props.auth.user"
                    href="/admin/register"
                    class="inline-block rounded-sm bg-[#1b1b18] px-8 py-3 text-sm font-medium text-white hover:bg-black dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white"
                >
                    Начать бесплатно
                </a>
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm bg-[#1b1b18] px-8 py-3 text-sm font-medium text-white hover:bg-black dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white"
                >
                    Перейти в кабинет
                </Link>
                <a
                    v-if="!$page.props.auth.user"
                    href="/admin/login"
                    class="inline-block rounded-sm border border-[#19140035] px-8 py-3 text-sm font-medium hover:border-[#1915014a] dark:border-[#3E3E3A] dark:hover:border-[#62605b]"
                >
                    У меня есть аккаунт
                </a>
            </div>

            <div class="mt-16 grid max-w-3xl grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="rounded-lg border border-[#e3e3e0] p-6 text-left dark:border-[#3E3E3A]">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="mb-1 font-medium">Быстрое создание</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Вставьте URL — получите короткую ссылку за секунду</p>
                </div>

                <div class="rounded-lg border border-[#e3e3e0] p-6 text-left dark:border-[#3E3E3A]">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="mb-1 font-medium">Статистика кликов</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">IP-адреса, даты и время каждого перехода</p>
                </div>

                <div class="rounded-lg border border-[#e3e3e0] p-6 text-left dark:border-[#3E3E3A]">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="mb-1 font-medium">Приватность</h3>
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Каждый видит только свои ссылки и статистику</p>
                </div>
            </div>
        </main>

        <footer class="mt-16 pb-8 text-xs text-[#706f6c] dark:text-[#A1A09A]">
            Built with Laravel + FilamentPHP
        </footer>
    </div>
</template>
