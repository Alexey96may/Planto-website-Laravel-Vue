<script setup lang="ts">
    import { Link, usePage } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import ParallaxCard from '@/Components/UI/ParallaxCard.vue';
    import { ProductWithCategory } from '@/types';

    interface Props {
        plant?: ProductWithCategory;
    }

    const { plant = {} as ProductWithCategory } = defineProps<Props>();
</script>

<template>
    <figure class="card slider-mini__card w-full" aria-label="Slider card">
        <div
            class="card__img-wrapper relative aspect-[1/1] w-full sm:h-64"
            aria-label="Slider card image"
        >
            <ParallaxCard :with-glare="false">
                <AppImage
                    :src="plant.image_url"
                    :alt="plant.title"
                    class="parallax-image h-full w-full object-contain drop-shadow-md transition-transform duration-500 md:drop-shadow-xl"
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
        transform: rotateX(calc(var(--my) * -6deg)) translateX(calc(var(--mx) * -10px)) scale(1.1);
        transition: transform 0.1s ease-out;
        will-change: transform;
    }
</style>
