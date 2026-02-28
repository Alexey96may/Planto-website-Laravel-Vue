<script setup lang="ts">
    import { ref } from 'vue';

    import { Link } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';
    import NavLink from '@/Components/NavLink.vue';
    import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
    import Toast from '@/Components/Toast.vue';

    const showingNavigationDropdown = ref(false);

    defineSlots<{
        default(props: {}): any;
        header(props: {}): any;
    }>();
</script>

<template>
    <div class="selection:bg-[#c5d86d] selection:text-[#1a1f16]">
        <Toast />
        <div class="min-h-screen bg-[#1a1f16]">
            <nav class="border-b border-white/5 bg-[#242b1f]/80 backdrop-blur-md sticky top-0 z-50">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        <div class="flex items-center gap-8">
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-8 w-auto fill-current text-[#c5d86d]"
                                    />
                                </Link>
                            </div>

                            <div class="hidden space-x-4 sm:flex">
                                <NavLink
                                    :href="'/'"
                                    :active="false"
                                    class="text-gray-400 hover:text-[#c5d86d]"
                                >
                                    Home
                                </NavLink>

                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                    class="text-gray-400 active:text-[#c5d86d]"
                                >
                                    My Account
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-2 rounded-xl border border-white/5 bg-[#1a1f16] px-4 py-2 text-sm font-medium text-gray-300 transition hover:border-[#c5d86d]/50 focus:outline-none"
                                        >
                                            {{ $page.props.auth.user.name }}
                                            <svg
                                                class="h-4 w-4 text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div
                                            class="bg-[#242b1f] border border-white/10 rounded-lg overflow-hidden shadow-2xl"
                                        >
                                            <DropdownLink
                                                :href="route('profile.edit')"
                                                class="text-gray-300 hover:bg-[#1a1f16] hover:text-[#c5d86d]"
                                            >
                                                Profile Settings
                                            </DropdownLink>
                                            <DropdownLink
                                                :href="route('logout')"
                                                method="post"
                                                as="button"
                                                class="text-gray-300 hover:bg-red-900/20 hover:text-red-400"
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
                                class="p-2 rounded-xl text-gray-400 hover:bg-white/5 transition"
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
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden bg-[#1a1f16] border-t border-white/5"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="'/'"> Home </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <header class="bg-transparent" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div class="text-[#c5d86d] font-bold tracking-tight text-2xl">
                        <slot name="header" />
                    </div>
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
    :deep(.nav-link-active) {
        color: #c5d86d !important;
        border-bottom: 2px solid #c5d86d !important;
    }
</style>
