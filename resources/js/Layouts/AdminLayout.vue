<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';
    import { usePage } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import Toast from '@/Components/Toast.vue';

    const currentComponent = computed(() => page.component);

    const page = usePage();

    defineSlots<{
        default(props: {}): any;
    }>();
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
                            currentComponent === 'Admin/Dashboard',
                    }"
                >
                    🏠 Dashboard
                </Link>

                <Link
                    :href="route('admin.products.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            currentComponent === 'Admin/Products/Index',
                    }"
                >
                    📦 Products
                </Link>

                <Link
                    :href="route('admin.categories.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            currentComponent === 'Admin/Сategories/Index',
                    }"
                >
                    📂 Categories
                </Link>

                <Link
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :href="route('admin.orders.index')"
                    :active="route().current('admin.orders.*')"
                >
                    🛒 Orders
                </Link>

                <Link
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :href="route('admin.comments.index')"
                    :active="route().current('admin.comments.*')"
                >
                    💬 Comments
                    <span v-if="!$page.props.pending_comments_count">{{
                        $page.props.pending_comments_count
                    }}</span>
                    <span
                        v-else
                        class="inline-flex items-center justify-center px-2 py-1 ml-1 text-xs font-bold leading-none text-red-500 animate-bounce"
                    >
                        ({{ $page.props.pending_comments_count }})
                    </span>
                </Link>

                <Link
                    :href="route('admin.users.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            currentComponent === 'Admin/Users/Index',
                    }"
                >
                    👥 Users
                </Link>

                <Link
                    :href="route('admin.navigation.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :active="route().current('admin.navigation.*')"
                >
                    🗺️ Navigation
                </Link>

                <Link
                    :href="route('admin.features.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :active="route().current('admin.features.*')"
                >
                    ✨ Features
                </Link>

                <Link
                    :href="route('admin.settings.index')"
                    class="block py-3 px-6 hover:bg-gray-800 transition"
                    :class="{
                        'bg-gray-800 border-l-4 border-green-500':
                            currentComponent === 'Admin/Settings/Index',
                    }"
                >
                    ⚙️ Settings
                </Link>

                <div class="border-t border-gray-800 mt-6 pt-6">
                    <Link
                        href="/"
                        class="block py-3 px-6 text-gray-400 hover:text-white transition"
                    >
                        🌐 View Website
                    </Link>
                </div>
            </nav>
        </aside>

        <main class="flex-1">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8">
                <div class="text-gray-600 font-medium italic">
                    Welcome back, {{ $page.props.auth.user.name }}
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-red-500 hover:underline"
                >
                    Logout
                </Link>
            </header>

            <div class="p-8">
                <slot />
            </div>
        </main>
    </div>
</template>
