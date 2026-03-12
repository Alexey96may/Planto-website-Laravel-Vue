<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import AppBuyButton from '@/Components/UI/AppBuyButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import ParallaxCard from '@/Components/UI/ParallaxCard.vue';
    import { Product } from '@/types';

    interface Props {
        plant?: Product;
        isInCart?: boolean;
        isProcessing?: boolean;
    }

    const { plant = {} as Product, isInCart = false, isProcessing = false } = defineProps<Props>();

    const emit = defineEmits<{
        (e: 'add-to-cart', product: Product): void;
    }>();
</script>

<template>
    <figure class="card top__card" aria-label="Top card">
        <div class="card__img-wrapper" aria-label="Top card image">
            <ParallaxCard :with-glare="false" class="cursor-move">
                <AppImage
                    :src="plant.image_url"
                    class="parallax-image drop-shadow-md transition-transform duration-500 hover:scale-110 md:drop-shadow-lg"
                    :alt="plant.title"
                ></AppImage>
            </ParallaxCard>
        </div>

        <h3 class="card__title" aria-label="Card title">
            <Link
                :href="'/shop/plant-' + plant.id"
                class="card__tag-title"
                aria-label="To {{ plant.title }}"
            >
                {{ plant.title }}
            </Link>
        </h3>

        <p class="card__descr card__descr--white line-clamp-5" aria-label="Plant description">
            {{ plant.description }}
        </p>

        <div class="card__buying" aria-label="Plant buying info">
            <div class="card__price" aria-label="Plant price">{{ plant.price }} $</div>

            <AppBuyButton
                :is-in-cart="isInCart"
                :is-processing="isProcessing"
                :disabled="plant.stock === 0"
                @click="emit('add-to-cart', plant)"
                class="button--square"
                aria-label="Add to cart"
            />
        </div>
    </figure>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .top__card {
        margin-top: calc(1rem * (110px / b.$basicFontSize));
        gap: calc(1rem * (30px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            gap: calc(1rem * (16px / b.$basicFontSize));
        }

        .card__descr {
            margin-bottom: calc(1rem * (6px / b.$basicFontSize));
            opacity: b.$opacity;

            @media (max-width: b.$mediaMobile) {
                margin-bottom: calc(1rem * (12px / b.$basicFontSize));
            }
        }

        .card__price {
            opacity: b.$opacity;
        }
    }

    .parallax-image {
        transform: rotateX(calc(var(--my) * -6deg)) translateX(calc(var(--mx) * -10px)) scale(1.1);
        transition: transform 0.1s ease-out;
        will-change: transform;
    }
</style>
