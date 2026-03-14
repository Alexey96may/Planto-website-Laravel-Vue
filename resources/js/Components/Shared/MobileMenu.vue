<script setup lang="ts">
    import { computed, ref, watch } from 'vue';

    import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
    import { useScroll, useScrollLock, useSwipe } from '@vueuse/core';

    import { useSound } from '@/composables/useSound';

    const isOpen = ref(false);
    const target = ref<HTMLElement | null>(null);

    const { playClick, playSlideMove } = useSound();

    const { isSwiping, direction, lengthX } = useSwipe(target, {
        onSwipeEnd(e, direction) {
            if (direction === 'right' && lengthX.value < -50) {
                isOpen.value = false;
            }
        },
    });

    const containerStyle = computed(() => {
        if (isSwiping.value && lengthX.value < 0) {
            return {
                transform: `translateX(${-lengthX.value}px)`,
                transition: 'none',
            };
        }
        return {};
    });

    const isLocked = useScrollLock(document.body);
    const toggleMenu = () => {
        isOpen.value = !isOpen.value;
        isLocked.value = isOpen.value;
    };

    watch(isOpen, (val) => {
        isLocked.value = val;
    });

    const scrollY = ref(0);
    const scrollHeight = ref(0);
    const clientHeight = ref(0);

    const handleScroll = (e: Event) => {
        const el = e.target as HTMLElement;
        scrollY.value = el.scrollTop;
        scrollHeight.value = el.scrollHeight;
        clientHeight.value = el.clientHeight;
    };

    const scrollProgress = computed(() => {
        const maxScroll = scrollHeight.value - clientHeight.value;
        if (maxScroll <= 0) return 0;

        return Math.min(100, Math.max(0, (scrollY.value / maxScroll) * 100));
    });
</script>

<template>
    <button
        type="button"
        @click="toggleMenu"
        @mousedown="playSlideMove"
        class="pointer-events-auto relative z-[90] block text-white opacity-75 outline-none focus:outline-none"
        aria-label="Toggle menu"
    >
        <div class="flex h-[26px] w-[32px] flex-col gap-2">
            <span
                :class="[
                    'h-[4px] w-full rounded-sm bg-current transition-all duration-300',
                    isOpen ? 'translate-y-[12px] rotate-45' : '',
                ]"
            ></span>
            <span
                :class="[
                    'ml-auto h-[4px] w-[70%] rounded-sm bg-current transition-all duration-300',
                    isOpen ? 'opacity-0' : '',
                ]"
            ></span>
            <span
                :class="[
                    'h-[4px] w-0 rounded-sm bg-current transition-all duration-300',
                    isOpen ? 'w-[32px] -translate-y-[12px] -rotate-45' : '',
                ]"
            ></span>
        </div>
    </button>

    <TransitionRoot :show="isOpen" as="template" appear>
        <Dialog as="div" @close="isOpen = false" class="relative z-[100]">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/30 backdrop-blur-md" aria-hidden="true" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-hidden">
                <div class="flex min-h-full items-stretch justify-end">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="translate-x-full"
                        enter-to="translate-x-0"
                        leave="duration-300 ease-in"
                        leave-from="translate-x-0"
                        leave-to="translate-x-full"
                    >
                        <DialogPanel
                            ref="target"
                            :style="containerStyle"
                            @scroll="handleScroll"
                            class="scrollbar-hide scrollbar-hide relative ml-auto flex h-screen w-full max-w-xs touch-pan-y flex-col overflow-y-auto bg-zinc-800 shadow-2xl"
                        >
                            <div class="relative w-full flex-1">
                                <div
                                    class="pointer-events-none sticky bottom-0 left-0 top-0 z-50 h-screen w-1 bg-zinc-700/30"
                                >
                                    <div
                                        class="absolute w-full rounded-full bg-emerald-500 transition-all duration-75 will-change-transform"
                                        :style="{
                                            height: '40px',
                                            top: `${scrollProgress}%`,
                                            transform: `translateY(-${scrollProgress}%)`,
                                        }"
                                    ></div>
                                </div>

                                <div
                                    class="pointer-events-none absolute right-0 top-0 z-[60] flex w-full justify-end px-6 py-9 sm:py-6"
                                >
                                    <button
                                        type="button"
                                        @mousedown="playClick"
                                        @click="isOpen = false"
                                        class="pointer-events-auto text-white opacity-75 outline-none transition-transform hover:scale-110"
                                    >
                                        <div class="relative h-6 w-6">
                                            <span
                                                class="absolute left-0 top-1/2 h-0.5 w-full -translate-y-1/2 rotate-45 bg-current"
                                            ></span>
                                            <span
                                                class="absolute left-0 top-1/2 h-0.5 w-full -translate-y-1/2 -rotate-45 bg-current"
                                            ></span>
                                        </div>
                                    </button>
                                </div>

                                <nav
                                    class="mt-[-100vh] flex flex-col gap-6 p-8 pt-24 text-xl font-medium text-zinc-200"
                                >
                                    <slot />
                                </nav>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<style lang="scss" scoped>
    .scrollbar-hide::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
    }

    .scrollbar-hide {
        -ms-overflow-style: none !important; /* IE and Edge */
        scrollbar-width: none !important; /* Firefox */
    }
</style>
