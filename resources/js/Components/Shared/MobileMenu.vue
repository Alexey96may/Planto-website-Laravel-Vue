<script setup lang="ts">
    import { computed, ref, watch } from 'vue';
    import { nextTick } from 'vue';

    import { TransitionChild, TransitionRoot } from '@headlessui/vue';
    import { useScrollLock, useSwipe } from '@vueuse/core';
    import gsap from 'gsap';

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

    watch(isOpen, async (val) => {
        if (val) {
            await nextTick();

            const items = document.querySelectorAll('.mobile-nav-wrapper > *');

            gsap.set(items, {
                x: 120,
                opacity: 0,
            });

            gsap.to(items, {
                x: 0,
                opacity: 1,
                duration: 0.5,
                stagger: 0.2,
                ease: 'power2.out',
                delay: 0.2,
            });
        }
    });
</script>

<template>
    <button
        type="button"
        @click="toggleMenu"
        @mousedown="playSlideMove"
        class="relative z-[110] block text-white opacity-75 outline-none focus:outline-none"
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
        <div class="relative z-[100]">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div @click="isOpen = false" class="fixed inset-0 bg-black/30 backdrop-blur-md" />
            </TransitionChild>

            <div class="pointer-events-none fixed inset-0 z-10 overflow-hidden">
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
                        <div
                            ref="target"
                            :style="containerStyle"
                            @scroll="handleScroll"
                            class="scrollbar-hide pointer-events-auto relative ml-auto flex h-screen w-full max-w-xs flex-col overflow-y-auto bg-zinc-800 shadow-2xl"
                        >
                            <div class="relative w-full flex-1">
                                <nav
                                    class="mobile-nav-wrapper flex flex-col gap-6 p-8 pt-24 text-xl font-medium text-zinc-200"
                                >
                                    <slot />
                                </nav>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </div>
        </div>
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
