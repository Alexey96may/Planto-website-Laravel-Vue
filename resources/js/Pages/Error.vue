<script setup>
    import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="min-h-screen bg-[#0b120c] flex items-center justify-center px-4 overflow-hidden relative"
    >
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-green-900/20 rounded-full blur-[120px]"
        ></div>

        <div class="text-center z-10">
            <h1
                class="text-[12rem] md:text-[18rem] font-bold leading-none text-white/5 select-none"
            >
                404
            </h1>

            <div class="-mt-12 md:-mt-20">
                <h2 class="text-2xl md:text-4xl font-light text-white mb-4">
                    Oops! You've wandered into
                    <span class="text-green-500 font-medium">wild jungle</span>
                </h2>
                <p class="text-gray-400 mb-10 max-w-md mx-auto">
                    It looks like this page hasn't grown yet or has been moved to another location.
                </p>

                <Link
                    href="/"
                    class="inline-block px-10 py-4 border border-white text-white rounded-full hover:bg-white hover:text-black transition-all duration-500 uppercase tracking-widest text-sm font-semibold"
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
