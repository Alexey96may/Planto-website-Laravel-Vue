<script setup lang="ts">
    import { computed, nextTick, ref, watch } from 'vue';

    import { TransitionChild, TransitionRoot } from '@headlessui/vue';
    import { useScrollLock, useSwipe } from '@vueuse/core';
    import gsap from 'gsap';

    import { useSound } from '@/composables/useSound';

    const isOpen = ref(false);
    const target = ref<HTMLElement | null>(null);
    const { playSlideMove } = useSound();

    const { isSwiping, lengthX } = useSwipe(target, {
        onSwipeEnd(e, direction) {
            if (direction === 'left' && lengthX.value > 50) {
                isOpen.value = false;
            }
        },
    });

    const containerStyle = computed(() => {
        if (isSwiping.value && lengthX.value > 0) {
            return {
                transform: `translateX(${-lengthX.value}px)`,
                transition: 'none',
            };
        }
        return {};
    });

    const isLocked = useScrollLock(typeof document !== 'undefined' ? document.body : null);

    const toggleMenu = () => {
        isOpen.value = !isOpen.value;
    };

    watch(isOpen, (val) => {
        isLocked.value = val;
    });

    const scrollY = ref(0);
    const handleScroll = (e: Event) => {
        const el = e.target as HTMLElement;
        scrollY.value = el.scrollTop;
    };

    watch(isOpen, async (val) => {
        if (val && typeof document !== 'undefined') {
            await nextTick();

            const items = document.querySelectorAll('.mobile-nav-wrapper > *');

            gsap.killTweensOf(items);

            gsap.fromTo(
                items,
                { x: -40, opacity: 0 },
                {
                    x: 0,
                    opacity: 1,
                    duration: 0.4,
                    stagger: 0.1,
                    ease: 'power2.out',
                    delay: 0.1,
                },
            );
        }
    });
</script>

<template>
    <button
        type="button"
        @click="toggleMenu"
        @mousedown="playSlideMove"
        class="relative z-[200] block text-white opacity-75 outline-none focus:outline-none"
        :aria-expanded="isOpen"
        aria-controls="mobile-menu"
        aria-label="Toggle navigation menu"
    >
        <div
            class="flex h-[18px] w-[24px] flex-col gap-[6px] md:h-[26px] md:w-[32px] md:gap-[8px]"
            aria-hidden="true"
        >
            <span
                :class="[
                    'h-[2px] w-full rounded-sm bg-current transition-all duration-300 md:h-[4px]',
                    isOpen ? 'translate-y-[8px] rotate-45 md:translate-y-[12px]' : '',
                ]"
            ></span>
            <span
                :class="[
                    'ml-auto h-[2px] w-[70%] rounded-sm bg-current transition-all duration-300 md:h-[4px]',
                    isOpen ? 'opacity-0' : '',
                ]"
            ></span>
            <span
                :class="[
                    'h-[2px] w-0 rounded-sm bg-current transition-all duration-300 md:h-[4px]',
                    isOpen
                        ? 'w-[24px] -translate-y-[8px] -rotate-45 md:w-[32px] md:-translate-y-[12px]'
                        : '',
                ]"
            ></span>
        </div>
    </button>

    <Teleport to="body">
        <TransitionRoot :show="isOpen" as="template" appear>
            <div id="mobile-menu" class="relative z-[100]" role="dialog" aria-modal="true">
                <TransitionChild
                    as="template"
                    enter="duration-300 ease-out"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="duration-200 ease-in"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <div
                        @click="isOpen = false"
                        class="fixed inset-0 bg-black/40 backdrop-blur-md"
                        aria-hidden="true"
                    />
                </TransitionChild>

                <div class="pointer-events-none fixed inset-0 z-10">
                    <div class="flex min-h-full items-stretch justify-start">
                        <TransitionChild
                            as="template"
                            enter="duration-400 ease-out"
                            enter-from="-translate-x-full"
                            enter-to="translate-x-0"
                            leave="duration-300 ease-in"
                            leave-from="translate-x-0"
                            leave-to="-translate-x-full"
                        >
                            <div
                                ref="target"
                                :style="containerStyle"
                                @scroll="handleScroll"
                                class="scrollbar-hide pointer-events-auto relative flex h-screen w-full flex-col overflow-y-auto bg-plant-green/90 shadow-2xl sm:mr-auto sm:max-w-xs"
                            >
                                <div
                                    class="pointer-events-none sticky top-0 z-50 h-24 w-full shrink-0 bg-gradient-to-b from-plant-green via-plant-green to-transparent"
                                />

                                <nav
                                    class="mobile-nav-wrapper flex flex-col gap-6 p-8 pt-0 text-sm font-bold uppercase tracking-widest text-zinc-100 md:text-xl"
                                >
                                    <slot />
                                </nav>
                            </div>
                        </TransitionChild>
                    </div>
                </div>
            </div>
        </TransitionRoot>
    </Teleport>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
        &::-webkit-scrollbar {
            display: none;
        }
    }

    @media (min-width: b.$mediaTablet) {
        .relative.z-\[100\] {
            display: none !important;
        }
    }
</style>
