<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import IconLogo from 'img/icons/favicon-admin.svg?component';
    import {
        ExternalLink,
        FolderTree,
        LayoutDashboard,
        Leaf,
        LogOut,
        Map,
        Menu,
        MessageSquare,
        Settings,
        ShoppingCart,
        Sparkles,
        UserRound,
        Users,
        X,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppToast from '@/Components/Shared/Toast.vue';

    const page = usePage();
    const currentComponent = computed(() => page.component);
    const isMobileMenuOpen = ref(false);

    const navItems = [
        {
            name: 'Dashboard',
            route: 'admin.dashboard',
            icon: LayoutDashboard,
            component: 'Admin/Dashboard',
        },
        {
            name: 'Products',
            route: 'admin.products.index',
            icon: Leaf,
            component: 'Admin/Products/Index',
        },
        {
            name: 'Categories',
            route: 'admin.categories.index',
            icon: FolderTree,
            component: 'Admin/Сategories/Index',
        },
        {
            name: 'Orders',
            route: 'admin.orders.index',
            icon: ShoppingCart,
            component: 'admin.orders.*',
        },
        {
            name: 'Comments',
            route: 'admin.comments.index',
            icon: MessageSquare,
            component: 'admin.comments.*',
            badge: 'pending_comments_count',
        },
        { name: 'Users', route: 'admin.users.index', icon: Users, component: 'Admin/Users/Index' },
        {
            name: 'Navigation',
            route: 'admin.navigation.index',
            icon: Map,
            component: 'admin.navigation.*',
        },
        {
            name: 'Features',
            route: 'admin.features.index',
            icon: Sparkles,
            component: 'admin.features.*',
        },
        {
            name: 'Settings',
            route: 'admin.settings.index',
            icon: Settings,
            component: 'Admin/Settings/Index',
        },
    ];

    const checkActive = (item: any) => {
        if (item.component.includes('*')) {
            return route().current(item.component);
        }
        return currentComponent.value === item.component;
    };
</script>

<template>
    <SeoMeta force-robots="noindex, nofollow" />

    <div class="flex min-h-screen flex-col bg-[#0f120e] text-zinc-300 lg:flex-row">
        <div
            class="sticky top-0 z-[60] flex items-center justify-between border-b border-white/5 bg-[#161b14] p-4 lg:hidden"
        >
            <div class="flex items-center gap-2 font-black italic text-white">
                <Link :href="route('admin.dashboard')">
                    <IconLogo
                        class="block h-9 w-auto fill-current text-[#c5d86d]"
                        aria-label="Plant Shop Logo"
                    />
                </Link>
                PLANTO <span class="text-[#c5d86d]">ADMIN</span>
            </div>
            <button
                @click="isMobileMenuOpen = !isMobileMenuOpen"
                class="p-2 text-zinc-400 outline-none"
            >
                <Menu v-if="!isMobileMenuOpen" />
                <X v-else />
            </button>
        </div>

        <aside
            :class="[isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0']"
            class="fixed inset-y-0 left-0 z-50 w-72 overflow-y-auto border-r border-white/5 bg-[#161b14] transition-transform duration-300 lg:static lg:block"
        >
            <div class="h-16 lg:hidden"></div>

            <div class="mb-4 hidden items-center gap-3 border-b border-white/5 p-8 lg:flex">
                <div class="rounded-xl p-2">
                    <Link :href="route('admin.dashboard')">
                        <IconLogo
                            class="block h-9 w-auto fill-current text-[#c5d86d]"
                            aria-label="Plant Shop Logo"
                        />
                    </Link>
                </div>
                <span class="text-lg font-black italic tracking-tighter text-white">
                    PLANTO <span class="text-[#c5d86d]">ADMIN</span>
                </span>
            </div>

            <nav class="space-y-1 px-4 py-4 lg:py-0">
                <div v-for="item in navItems" :key="item.name">
                    <Link
                        :href="route(item.route)"
                        class="group flex items-center justify-between rounded-xl px-4 py-3 transition-all duration-200"
                        :class="[
                            checkActive(item)
                                ? 'bg-[#c5d86d] font-bold text-[#0f120e] shadow-lg shadow-[#c5d86d]/10'
                                : 'text-zinc-400 hover:bg-white/5 hover:text-white',
                        ]"
                        @click="isMobileMenuOpen = false"
                    >
                        <div class="flex items-center gap-3">
                            <component
                                :is="item.icon"
                                class="h-5 w-5"
                                :class="
                                    checkActive(item)
                                        ? ''
                                        : 'text-zinc-500 group-hover:text-[#c5d86d]'
                                "
                            />
                            {{ item.name }}
                        </div>

                        <span
                            v-if="item.badge && $page.props[item.badge]"
                            class="animate-pulse rounded-md px-2 py-0.5 text-[10px] font-black uppercase tracking-tighter"
                            :class="
                                checkActive(item)
                                    ? 'bg-[#0f120e] text-[#c5d86d]'
                                    : 'bg-red-500/20 text-red-500'
                            "
                        >
                            {{ $page.props[item.badge] }}
                        </span>
                    </Link>
                </div>

                <div class="mt-8 flex flex-col gap-4 border-t border-white/5 px-4 pb-10 pt-8">
                    <Link
                        :href="route('dashboard')"
                        class="flex items-center gap-3 text-sm text-zinc-500 transition hover:text-white"
                    >
                        <UserRound class="h-4 w-4" />
                        My Dashboard
                    </Link>
                    <Link
                        href="/"
                        class="flex items-center gap-3 text-sm text-zinc-500 transition hover:text-white"
                    >
                        <ExternalLink class="h-4 w-4" />
                        View Website
                    </Link>
                </div>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <header
                class="sticky top-0 z-30 hidden h-20 items-center justify-between border-b border-white/5 bg-[#0f120e]/80 px-10 backdrop-blur-md lg:flex"
            >
                <div class="text-sm font-medium text-zinc-500">
                    Welcome back,
                    <Link :href="route('dashboard')" class="ml-1 text-white">{{
                        $page.props.auth.user.name
                    }}</Link>
                </div>
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center gap-2 rounded-xl border border-red-500/20 px-4 py-2 text-sm font-bold text-red-500 transition-all hover:bg-red-500 hover:text-white"
                >
                    <LogOut class="h-4 w-4" /> Logout
                </Link>
            </header>

            <main class="p-4 lg:p-10">
                <div class="mx-auto max-w-[1600px]">
                    <slot />
                    <AppToast />
                </div>
            </main>
        </div>

        <div
            v-if="isMobileMenuOpen"
            @click="isMobileMenuOpen = false"
            class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
        ></div>
    </div>
</template>
