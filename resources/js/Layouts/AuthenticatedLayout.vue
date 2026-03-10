<script setup lang="ts">
    import { ref } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import IconLogo from 'img/icons/logo.svg?component';
    import { ChevronDownIcon, LogOutIcon, UserCircleIcon } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import ApplicationLogo from '@/Components/UI/ApplicationLogo.vue';
    import Dropdown from '@/Components/UI/Dropdown.vue';
    import DropdownLink from '@/Components/UI/DropdownLink.vue';
    import NavLink from '@/Components/UI/NavLink.vue';
    import ResponsiveNavLink from '@/Components/UI/ResponsiveNavLink.vue';

    const showingNavigationDropdown = ref(false);
</script>

<template>
    <div
        class="font-sans text-zinc-300 antialiased selection:bg-[#c5d86d] selection:text-[#1a1f16]"
    >
        <div class="min-h-screen bg-[#1a1f16]">
            <nav class="sticky top-0 z-50 border-b border-white/5 bg-[#1a1f16]/60 backdrop-blur-xl">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-20 items-center justify-between">
                        <div class="flex items-center gap-10">
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
                                <NavLink :href="'/'" :active="false"> Store </NavLink>
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    My Garden
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
                                                class="flex h-6 w-6 items-center justify-center rounded-full bg-[#c5d86d]/20"
                                            >
                                                <span class="text-[10px] text-[#c5d86d]">{{
                                                    $page.props.auth.user.name.charAt(0)
                                                }}</span>
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
                                class="rounded-2xl p-3 text-zinc-400 transition-all hover:bg-white/5 active:scale-90"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-show="showingNavigationDropdown"
                    class="border-t border-white/5 bg-[#1a1f16]/95 backdrop-blur-md sm:hidden"
                >
                    <div class="space-y-2 px-4 pb-6 pt-4">
                        <ResponsiveNavLink :href="'/'">Store</ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            >My Garden</ResponsiveNavLink
                        >
                        <div class="mt-4 border-t border-white/5 pt-4">
                            <div class="px-4 py-2 text-xs font-bold uppercase text-zinc-600">
                                Manage
                            </div>
                            <ResponsiveNavLink :href="route('profile.edit')"
                                >Profile Settings</ResponsiveNavLink
                            >
                        </div>
                    </div>
                </div>
            </nav>

            <header class="relative overflow-hidden" v-if="$slots.header">
                <div
                    class="absolute left-1/2 top-0 h-px w-full -translate-x-1/2 bg-gradient-to-r from-transparent via-[#c5d86d]/20 to-transparent"
                ></div>

                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-black tracking-tight text-[#c5d86d] sm:text-4xl">
                        <slot name="header" />
                    </h1>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
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
