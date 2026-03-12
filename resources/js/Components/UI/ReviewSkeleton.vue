<template>
    <div
        class="relative overflow-hidden rounded-[1rem] border border-white/5 bg-[#161b14] p-6 sm:p-10 xl:rounded-[2rem]"
    >
        <div
            class="shimmer-layer absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent"
        ></div>

        <div class="relative z-10">
            <div class="mb-8 flex items-center justify-between border-b border-white/5 pb-6">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-full bg-zinc-700/50"></div>
                    <div class="flex flex-col gap-2">
                        <div class="h-4 w-32 rounded bg-zinc-700/50"></div>
                        <div class="h-3 w-20 rounded bg-zinc-700/50"></div>
                    </div>
                </div>
                <div class="hidden h-8 w-24 rounded-full bg-zinc-700/50 md:block"></div>
            </div>
            <div class="space-y-3">
                <div class="h-4 w-full rounded bg-zinc-700/50"></div>
                <div class="h-4 w-[90%] rounded bg-zinc-700/50"></div>
                <div class="h-4 w-[40%] rounded bg-zinc-700/50"></div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .shimmer-layer {
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }
</style>
