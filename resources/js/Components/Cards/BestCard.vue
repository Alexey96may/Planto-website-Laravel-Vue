<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';

    import IconArrowRight from 'img/icons/arrow-right.svg?component';

    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import ParallaxCard from '@/Components/UI/ParallaxCard.vue';
    import { useSound } from '@/composables/useSound';
    import { Feature } from '@/types';

    const {
        feature = {} as Feature,
        index = 0,
        total = 0,
    } = defineProps<{
        feature: Feature;
        index: number;
        total: number;
    }>();

    const emit = defineEmits(['next', 'prev']);

    const formatNum = (num: number) => (num + 1).toString().padStart(2, '0');
    const totalNum = computed(() => total.toString().padStart(2, '0'));

    const { playSlideMove } = useSound();
</script>

<template>
    <figure
        class="best__card flex h-full w-full flex-col gap-4 overflow-visible rounded-[2rem] pb-8 pr-8 shadow-sm transition-shadow hover:shadow-md lg:flex-row lg:gap-6"
    >
        <div
            class="best__card-image relative mx-auto -mt-24 mb-6 aspect-square h-auto w-full max-w-48 flex-1 flex-grow-0 overflow-hidden lg:h-96 lg:w-1/2 lg:max-w-none lg:flex-shrink-0"
        >
            <ParallaxCard :with-glare="false" class="ax-w-48 cursor-move">
                <AppImage
                    :src="feature.image_url"
                    :alt="feature.title"
                    class="parallax-image h-full w-full object-contain drop-shadow-sm"
                />
            </ParallaxCard>
        </div>

        <div class="flex w-full flex-grow flex-col gap-4 lg:w-1/2 lg:flex-shrink-0 lg:pt-8">
            <h3 class="best__card-title mb-4">
                <Link
                    :href="feature.link"
                    class="text-xl font-semibold transition-colors hover:text-emerald-600"
                >
                    {{ feature.title }}
                </Link>
            </h3>

            <div
                class="best__card-description card__inform max-h-44 flex-grow text-sm leading-relaxed text-zinc-300 lg:mb-8"
                v-if="feature.description"
                v-html="feature.description"
            ></div>

            <div class="mt-auto flex flex-wrap items-center justify-between gap-4 pt-4">
                <AppExploreButton :href="feature.link" class="button--rect scale-90" />

                <div class="flex items-center gap-3 text-sm font-medium">
                    <button
                        @mousedown="playSlideMove"
                        @click="emit('prev')"
                        class="transition-colors hover:text-emerald-500"
                    >
                        <IconArrowRight
                            class="pointer-events-none h-5 w-5 rotate-180 fill-zinc-300"
                        />
                    </button>

                    <span class="whitespace-nowrap text-zinc-100">
                        <span class="text-zinc-400">{{ formatNum(index) }}</span> / {{ totalNum }}
                    </span>

                    <button
                        @mousedown="playSlideMove"
                        @click="emit('next')"
                        class="transition-colors hover:text-emerald-500"
                    >
                        <IconArrowRight class="pointer-events-none h-5 w-5 fill-zinc-300" />
                    </button>
                </div>
            </div>
        </div>
    </figure>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .best__card {
        background: url('../../../images/slider_bg.png') center / 100% 100% no-repeat;
        border-radius: 0;
        padding: 0 4rem 4rem 2rem;

        position: relative;
        z-index: 1;

        overflow: visible !important;

        @media (max-width: b.$mediaMobile) {
            background: url('../../../images/slider_bg-mobile.png') center / 100% 100% no-repeat;
            padding: 0 1rem 2rem 1rem;
        }
    }

    .card__inform {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1rem;
        padding: 0 !important;

        p {
            padding: 0 !important;
        }
    }

    .card__descr {
        padding: 0;
    }

    .relative.aspect-square {
        flex: 1 1 50%;
    }

    figure,
    div,
    h3 {
        transition:
            box-shadow 0.3s ease,
            border-color 0.3s ease !important;
    }

    .relative,
    h3,
    .mb-8,
    .mt-auto {
        will-change: transform, opacity, filter;
    }

    .parallax-image {
        transform: rotateX(calc(var(--my) * -6deg)) rotateY(calc(var(--mx) * 6deg))
            translateX(calc(var(--mx) * -10px)) translateY(calc(var(--my) * -10px)) scale(1.1);

        transition: transform 0.3s ease-out;
        will-change: transform;
    }
</style>
