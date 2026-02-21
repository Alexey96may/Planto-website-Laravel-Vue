<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    stats: Object,
});
</script>

<template>
    <AdminLayout
        ><Head title="Панель управления" />

        <div class="p-8 max-w-7xl mx-auto">
            <div
                class="p-6 bg-gray-900 rounded-2xl text-white flex justify-between items-center"
            >
                <div>
                    <h3 class="text-lg font-bold">Быстрое действие</h3>
                    <p class="text-gray-400 text-sm">
                        Хотите добавить новый цветок в магазин?
                    </p>
                </div>
                <Link
                    :href="route('admin.products.create')"
                    class="bg-green-500 hover:bg-green-600 px-6 py-2 rounded-xl font-bold transition"
                >
                    + Добавить товар
                </Link>
            </div>
            <br />
            <br />
            <h1 class="text-3xl font-bold mb-8 text-gray-800">
                Панель управления
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <Link
                    :href="route('admin.products.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-green-600 bg-green-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        📦
                    </div>
                    <h2 class="text-xl font-bold mb-2">Товары</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Управление ассортиментом ({{ stats.products_count }}
                        шт.)
                    </p>
                    <span class="text-green-600 font-medium flex items-center"
                        >Перейти →</span
                    >
                </Link>

                <Link
                    :href="route('admin.orders.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-blue-600 bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        👥
                    </div>
                    <h2 class="text-xl font-bold mb-2">Заказы</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Список Заказов. Новых: (Пока не доступно)
                    </p>
                    <span class="text-blue-600 font-medium flex items-center"
                        >Перейти →</span
                    >
                </Link>

                <Link
                    :href="route('admin.comments.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-blue-600 bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        👥
                    </div>
                    <h2 class="text-xl font-bold mb-2">Комменты</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Список Комментов. Новых: ({{
                            stats.pending_comments_count
                        }})
                    </p>
                    <span class="text-blue-600 font-medium flex items-center"
                        >Перейти →</span
                    >
                </Link>

                <Link
                    :href="route('admin.users.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-blue-600 bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        👥
                    </div>
                    <h2 class="text-xl font-bold mb-2">Пользователи</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Список клиентов и права доступа ({{
                            stats.users_count
                        }})
                    </p>
                    <span class="text-blue-600 font-medium flex items-center"
                        >Перейти →</span
                    >
                </Link>

                <Link
                    :href="route('admin.navigation.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-blue-600 bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        ⚙️
                    </div>
                    <h2 class="text-xl font-bold mb-2">Навигация</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Управление навигацией сайта
                    </p>
                    <span class="text-blue-600 font-medium flex items-center"
                        >Перейти →</span
                    >
                </Link>

                <Link
                    :href="route('admin.features.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-blue-600 bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        ⚙️
                    </div>
                    <h2 class="text-xl font-bold mb-2">Фичи</h2>
                    <p class="text-gray-500 text-sm mb-4">Список фичей</p>
                    <span class="text-blue-600 font-medium flex items-center"
                        >Перейти →</span
                    >
                </Link>

                <Link
                    :href="route('admin.settings.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-purple-600 bg-purple-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        ⚙️
                    </div>
                    <h2 class="text-xl font-bold mb-2">Общие Настройки</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Телефон, email и общая информация
                    </p>
                    <span class="text-purple-600 font-medium flex items-center"
                        >Перейти →</span
                    >
                </Link>
            </div>
        </div>
    </AdminLayout>
</template>
