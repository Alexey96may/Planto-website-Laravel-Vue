<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import { Seo } from '@/types';

    interface Props {
        status: number;
        seo?: Seo;
    }

    const props = defineProps<Props>();
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <div
        class="relative flex min-h-screen items-center justify-center overflow-hidden bg-[#0b120c] px-4 py-6"
        role="main"
    >
        <div
            class="absolute left-1/2 top-1/2 h-[500px] max-w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-green-900/20 blur-[120px]"
            aria-hidden="true"
        ></div>

        <div class="z-10 text-center">
            <h1
                class="select-none text-[50vw] font-bold leading-none text-white/5 lg:text-[12rem]"
                aria-hidden="true"
            >
                {{ props.status }}
            </h1>

            <div class="-mt-10 md:-mt-16">
                <h2 class="mb-8 text-2xl font-light text-white md:text-4xl">
                    <span class="sr-only">Error {{ props.status }}: </span>
                    Oops! You've wandered into
                    <span class="font-medium text-green-500">wild jungle</span>
                </h2>

                <p class="mx-auto mb-10 max-w-md text-gray-400">
                    It looks like this page hasn't grown yet or has been moved to another location.
                </p>

                <Link
                    href="/"
                    class="inline-block rounded-full border border-white px-10 py-4 text-sm font-semibold uppercase tracking-widest text-white transition-all duration-500 hover:bg-white hover:text-black"
                    :aria-label="`Error ${props.status}. Return to the home page`"
                >
                    Return home
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
    h2 {
        animation: sway 6s ease-in-out infinite;
    }

    @keyframes sway {
        0%,
        100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }
</style>
