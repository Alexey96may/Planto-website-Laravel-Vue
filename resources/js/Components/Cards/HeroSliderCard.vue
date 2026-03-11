<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import { ProductWithCategory, SharedData } from '@/types';

    interface Props {
        plant?: ProductWithCategory;
    }

    const { plant = {} as ProductWithCategory } = defineProps<Props>();

    const page = usePage<SharedData>();

    const isInCart = computed(() => {
        return page.props?.cart_ids.includes(plant.id);
    });
</script>

<template>
    <figure class="card slider-mini__card w-full" aria-label="Slider card">
        <div
            class="card__img-wrapper relative aspect-[1/1] w-full sm:h-64"
            aria-label="Slider card image"
        >
            <AppImage
                :src="plant.image_url"
                :alt="plant.title"
                class="h-full w-full object-contain transition-transform duration-500 hover:scale-105"
            />
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
</style>
