<template>
    <div
        class="grid animate-pulse grid-cols-1 items-center gap-4 border-b border-white/5 px-8 py-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-[80px_1fr_120px_120px_100px_140px]"
    >
        <div class="flex justify-center 2xl:justify-start">
            <div class="h-14 w-14 rounded-xl bg-white/5"></div>
        </div>

        <div class="space-y-2">
            <div class="mx-auto h-4 w-3/4 rounded bg-white/10 2xl:mx-0"></div>
            <div class="mx-auto h-3 w-1/2 rounded bg-white/5 2xl:mx-0"></div>
        </div>

        <div class="flex justify-center 2xl:justify-end">
            <div class="h-5 w-16 rounded bg-white/10"></div>
        </div>

        <div class="flex justify-center gap-2">
            <div class="h-6 w-12 rounded-full bg-white/5"></div>
            <div class="h-6 w-10 rounded-lg bg-white/5"></div>
        </div>

        <div class="flex justify-center">
            <div class="h-9 w-20 rounded-xl bg-white/5"></div>
        </div>

        <div class="flex justify-center gap-2 2xl:justify-end">
            <div class="h-10 w-10 rounded-xl bg-white/5"></div>
            <div class="h-10 w-10 rounded-xl bg-white/5"></div>
        </div>
    </div>
</template>

<style scoped>
    .animate-pulse {
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.03) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
</style>
