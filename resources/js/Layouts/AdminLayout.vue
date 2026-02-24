<script setup>
import { Link } from "@inertiajs/vue3";
import Toast from "@/Components/Toast.vue";
import NavLink from "@/Components/NavLink.vue";
</script>

<template>
    <Toast />
    <div class="min-h-screen bg-gray-100 flex">
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <div class="p-6 text-2xl font-bold border-b border-gray-800">
                🌿 Planto <span class="text-green-500">Admin</span>
            </div>
            <nav class="mt-6">
                <Link
                    :href="route('admin.dashboard')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            $page.component === 'Admin/Dashboard',
                    }"
                >
                    🏠 Главная
                </Link>
                <Link
                    :href="route('admin.products.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            $page.component === 'Admin/Products/Index',
                    }"
                >
                    📦 Товары
                </Link>

                <Link
                    :href="route('admin.categories.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            $page.component === 'Admin/Сategories/Index',
                    }"
                >
                    📦 Категории
                </Link>

                <Link
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :href="route('admin.orders.index')"
                    :active="route().current('admin.orders.*')"
                >
                    👥 Заказы
                </Link>
                <Link
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :href="route('admin.comments.index')"
                    :active="route().current('admin.comments.*')"
                >
                    👥 Комментарии
                </Link>
                <Link
                    :href="route('admin.users.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            $page.component === 'Admin/Users/Index',
                    }"
                >
                    👥 Пользователи
                </Link>
                <Link
                    :href="route('admin.navigation.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :active="route().current('admin.navigation.*')"
                >
                    ⚙️ Навигация
                </Link>

                <Link
                    :href="route('admin.features.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :active="route().current('admin.features.*')"
                >
                    ⚙️ Фичи
                </Link>
                <Link
                    :href="route('admin.settings.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            $page.component === 'Admin/Settings/Index',
                    }"
                    :active="route().current('admin.comments.*')"
                >
                    ⚙️ Общие Настройки
                </Link>
                <div class="border-t border-gray-800 mt-6 pt-6">
                    <Link
                        href="/"
                        class="block py-3 px-6 text-gray-400 hover:text-white transition"
                    >
                        🌐 На сайт
                    </Link>
                </div>
            </nav>
        </aside>

        <main class="flex-1">
            <header
                class="bg-white shadow-sm h-16 flex items-center justify-between px-8"
            >
                <div class="text-gray-600 font-medium italic">
                    Добро пожаловать, {{ $page.props.auth.user.name }}
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-red-500 hover:underline"
                >
                    Выйти
                </Link>
            </header>

            <div class="p-8">
                <slot />
            </div>
        </main>
    </div>
</template>
