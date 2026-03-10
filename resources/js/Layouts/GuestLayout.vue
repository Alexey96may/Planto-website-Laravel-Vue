<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import { HomeIcon } from '@heroicons/vue/24/outline';
    import IconLogo from 'img/icons/logo.svg?component';

    defineSlots<{
        default(props: {}): any;
    }>();
</script>

<template>
    <div
        class="relative flex min-h-screen flex-col items-center justify-center bg-plant-shop px-6 py-12 selection:bg-emerald-500/30"
    >
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div
                class="absolute -left-[10%] -top-[10%] h-[40%] w-[40%] rounded-full bg-emerald-900/20 blur-[120px]"
            ></div>
            <div
                class="absolute -bottom-[10%] -right-[10%] h-[40%] w-[40%] rounded-full bg-zinc-900 blur-[120px]"
            ></div>
        </div>

        <div class="relative z-10 mb-8 transition-transform duration-500 hover:scale-105">
            <Link href="/" class="flex items-center gap-2 sm:gap-4">
                <IconLogo
                    class="inline-block h-10 w-10 fill-current text-emerald-600 drop-shadow-[0_0_5px_rgba(16,185,129,0.2)] sm:h-20 sm:w-20"
                    aria-label="Plant Shop Logo"
                />
                <span class="logo__text text-2xl sm:text-4xl" aria-label="Logo text">{{
                    $page.props.settings?.site_name
                }}</span>
            </Link>
        </div>

        <div class="relative z-10 w-full max-w-md">
            <div
                class="overflow-hidden rounded-[1rem] border border-zinc-700 bg-zinc-900/40 p-8 shadow-2xl backdrop-blur-xl sm:rounded-[2.5rem] sm:p-12"
            >
                <slot />
            </div>

            <div class="mt-8 text-center">
                <Link
                    href="/"
                    class="inline-flex items-center gap-2 text-sm font-medium text-zinc-500 transition-colors hover:text-emerald-500"
                >
                    <HomeIcon class="h-4 w-4" />
                    Back to the garden
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
    div {
        animation: layoutReveal 0.6s ease-out forwards;
    }

    @keyframes layoutReveal {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
