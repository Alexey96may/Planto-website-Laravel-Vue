<script setup lang="ts">
    import { ref, watch } from 'vue';

    import { Link } from '@inertiajs/vue3';

    import IconLogo from 'img/icons/logo.svg?component';
    import { ChevronDownIcon } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import Footer from '@/Components/Sections/Footer.vue';
    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppToast from '@/Components/Shared/Toast.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import Dropdown from '@/Components/UI/Dropdown.vue';
    import DropdownLink from '@/Components/UI/DropdownLink.vue';
    import NavLink from '@/Components/UI/NavLink.vue';
    import ResponsiveNavLink from '@/Components/UI/ResponsiveNavLink.vue';

    const showingNavigationDropdown = ref(false);

    watch(showingNavigationDropdown, (isActive) => {
        if (isActive) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });
</script>

<template>
    <SeoMeta />

    <div
        class="font-sans text-zinc-300 antialiased selection:bg-[#c5d86d] selection:text-[#1a1f16]"
    >
        <div class="min-h-screen bg-plant-shop">
            <nav class="sticky top-0 z-50 border-b border-white/5 bg-[#1a1f16]/60 backdrop-blur-xl">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-20 items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div
                                class="flex shrink-0 transform items-center transition hover:scale-105"
                            >
                                <Link :href="route('home')">
                                    <IconLogo
                                        class="block h-9 w-auto fill-current text-[#c5d86d]"
                                        aria-label="Plant Shop Logo"
                                    />
                                </Link>
                            </div>

                            <div class="hidden space-x-6 sm:flex">
                                <NavLink :href="route('home')" :active="false"> Store </NavLink>

                                <NavLink
                                    v-if="$page.props.auth.user.role === 'admin'"
                                    :href="route('admin.dashboard')"
                                    :active="route().current('admin.dashboard')"
                                >
                                    Admin Panel
                                </NavLink>

                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    My Garden
                                </NavLink>

                                <NavLink
                                    :href="route('orders.user')"
                                    :active="route().current('orders.user')"
                                >
                                    My Orders
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="56">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-2.5 text-sm font-medium text-zinc-200 transition-all hover:border-[#c5d86d]/30 hover:bg-white/10 focus:outline-none"
                                        >
                                            <div
                                                class="flex h-6 w-6 items-center justify-center overflow-hidden rounded-full bg-[#c5d86d]/20"
                                            >
                                                <AppImage
                                                    :src="$page.props.auth.user.avatar_url || ''"
                                                    alt="User photo"
                                                >
                                                </AppImage>
                                            </div>
                                            {{ $page.props.auth.user.name }}
                                            <ChevronDownIcon class="h-4 w-4 text-zinc-500" />
                                        </button>
                                    </template>

                                    <template #content>
                                        <div
                                            class="min-w-[200px] overflow-hidden rounded-2xl border border-white/10 bg-[#242b1f] p-1.5 shadow-2xl"
                                        >
                                            <div class="mb-1 border-b border-white/5 px-4 py-3">
                                                <p
                                                    class="text-xs font-bold uppercase tracking-wider text-zinc-500"
                                                >
                                                    Account
                                                </p>
                                            </div>

                                            <DropdownLink
                                                :href="route('profile.edit')"
                                                class="flex items-center gap-2 rounded-xl"
                                            >
                                                Profile Settings
                                            </DropdownLink>

                                            <div class="my-1 border-t border-white/5"></div>

                                            <DropdownLink
                                                :href="route('logout')"
                                                method="post"
                                                as="button"
                                                class="flex items-center gap-2 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300"
                                            >
                                                Log Out
                                            </DropdownLink>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="relative h-10 w-10 rounded-2xl p-2 text-zinc-400 transition-all hover:bg-white/5 active:scale-90"
                            >
                                <div class="relative flex items-center justify-center">
                                    <span
                                        :class="
                                            showingNavigationDropdown
                                                ? 'translate-y-0 rotate-45'
                                                : '-translate-y-1.5'
                                        "
                                        class="absolute block h-0.5 w-5 transform bg-current transition duration-300 ease-in-out"
                                    ></span>
                                    <span
                                        :class="
                                            showingNavigationDropdown ? 'opacity-0' : 'opacity-100'
                                        "
                                        class="absolute block h-0.5 w-5 transform bg-current transition duration-300 ease-in-out"
                                    ></span>
                                    <span
                                        :class="
                                            showingNavigationDropdown
                                                ? 'translate-y-0 -rotate-45'
                                                : 'translate-y-1.5'
                                        "
                                        class="absolute block h-0.5 w-5 transform bg-current transition duration-300 ease-in-out"
                                    ></span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="translate-y-[-10px] opacity-0"
                    enter-to-class="translate-y-0 opacity-100"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="translate-y-0 opacity-100"
                    leave-to-class="translate-y-[-10px] opacity-0"
                >
                    <div
                        v-if="showingNavigationDropdown"
                        class="fixed inset-x-0 bottom-0 top-20 z-40 overflow-y-auto border-b border-white/5 bg-[#1a1f16]/95 p-4 pb-20 backdrop-blur-xl sm:hidden"
                        style="top: 80px; height: calc(100vh - 80px)"
                    >
                        <div class="space-y-1 rounded-xl bg-emerald-700/40 p-2 text-emerald-100">
                            <ResponsiveNavLink
                                :href="route('home')"
                                :active="route().current('home')"
                            >
                                Store
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                v-if="$page.props.auth.user.role === 'admin'"
                                :href="route('admin.dashboard')"
                                :active="route().current('admin.dashboard')"
                                class="text-[#c5d86d]"
                            >
                                Admin Panel
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                            >
                                My Garden
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                :href="route('orders.user')"
                                :active="route().current('orders.user')"
                            >
                                My Orders
                            </ResponsiveNavLink>
                        </div>

                        <div class="mt-4 border-t border-white/5 pt-4">
                            <div class="flex items-center px-4 py-3">
                                <div
                                    class="h-10 w-10 overflow-hidden rounded-xl border border-[#c5d86d]/30"
                                >
                                    <AppImage :src="$page.props.auth.user.avatar_url || ''" />
                                </div>
                                <div class="ms-3">
                                    <div class="text-sm font-bold text-white">
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div class="text-xs font-medium text-zinc-500">
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-2 space-y-1">
                                <ResponsiveNavLink
                                    :href="route('profile.edit')"
                                    :active="route().current('profile.edit')"
                                >
                                    Profile Settings
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="w-full text-left text-red-400"
                                >
                                    Log Out
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </Transition>
            </nav>

            <header class="relative overflow-hidden bg-plant-shop" v-if="$slots.header">
                <div
                    class="absolute left-1/2 top-0 h-px w-full -translate-x-1/2 bg-gradient-to-r from-transparent via-[#c5d86d]/20 to-transparent"
                ></div>

                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-black tracking-tight text-[#c5d86d] sm:text-4xl">
                        <slot name="header" />
                    </h1>
                </div>
            </header>

            <main class="mx-auto max-w-7xl bg-plant-shop px-4 pb-20 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>

        <AppToast />

        <Footer />
    </div>
</template>

<style scoped>
    :deep(.nav-link-active) {
        color: #c5d86d !important;
        position: relative;
    }
    :deep(.nav-link-active)::after {
        content: '';
        position: absolute;
        bottom: -24px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #c5d86d;
        box-shadow: 0 0 10px #c5d86d;
    }
</style>
