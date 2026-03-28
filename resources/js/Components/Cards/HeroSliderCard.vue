<script setup lang="ts">
    import { Link, usePage } from '@inertiajs/vue3';

    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import ParallaxCard from '@/Components/UI/ParallaxCard.vue';
    import { ProductWithCategory } from '@/types';

    interface Props {
        plant?: ProductWithCategory;
    }

    const { plant = {} as ProductWithCategory } = defineProps<Props>();

    const isLoaded = ref(false);
</script>

<template>
    <figure class="card slider-mini__card w-full" aria-label="Slider card">
        <div
            class="card__img-wrapper relative aspect-[1/1] w-full sm:h-64"
            aria-label="Slider card image"
        >
            <ParallaxCard :with-glare="false" :intensity="8">
                <div
                    v-if="!isLoaded"
                    class="skeleton-loader absolute inset-0 z-10 rounded-xl"
                ></div>

                <AppImage
                    :src="plant.optimized_images"
                    :alt="plant.title"
                    @load="isLoaded = true"
                    class="parallax-image h-full w-full object-contain drop-shadow-md transition-transform duration-500 md:drop-shadow-xl"
                    :class="{ 'opacity-0': !isLoaded, 'opacity-100': isLoaded }"
                />
            </ParallaxCard>
        </div>

        <div class="flex flex-col gap-2">
            <Link
                v-if="plant.category?.title"
                :href="route('shop', { category: plant.category?.slug })"
                class="card__tag card__tag-link text-zinc-300"
                :aria-label="plant.category?.title"
                >{{ plant.category?.title }}</Link
            >
            <h3
                class="card__title card__tag-title text-xl text-zinc-100"
                aria-label="Slider card title"
            >
                <Link
                    :href="route('shop.show', plant.id)"
                    class="card__tag-title"
                    :aria-label="plant.title"
                    >{{ plant.title }}</Link
                >
            </h3>
        </div>

        <AppExploreButton
            :href="route('shop.show', plant.id)"
            class="button--rect card__button mt-auto"
            :aria-label="plant.title"
        >
            Buy Now
        </AppExploreButton>
    </figure>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .skeleton-loader {
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0.05) 25%,
            rgba(255, 255, 255, 0.1) 50%,
            rgba(255, 255, 255, 0.05) 75%
        );
        background-size: 200% 100%;
        animation: skeleton-pulse 1.5s infinite linear;
    }

    @keyframes skeleton-pulse {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }

    .card {
        margin-top: 20%;

        @media (max-width: b.$mediaMobile) {
            gap: 0.65rem;
            padding-bottom: 4rem;
            max-width: unset;
        }
    }

    .card__img-wrapper {
        margin-top: -20%;

        @media (max-width: b.$mediaMobile) {
            max-width: unset;
        }
    }

    .parallax-image {
        transform: rotateX(calc(var(--my) * -6deg)) rotateY(calc(var(--mx) * 6deg))
            translateX(calc(var(--mx) * -10px)) translateY(calc(var(--my) * -10px)) scale(1.1);
        transition:
            transform 0.3s ease-out,
            opacity 0.5s ease-in;
        will-change: transform, opacity;
    }
</style>
