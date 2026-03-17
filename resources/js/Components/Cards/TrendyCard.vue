<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import IconBag from 'img/icons/bag.svg?component';
    import { route } from 'ziggy-js';

    import AppBuyButton from '@/Components/UI/AppBuyButton.vue';
    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
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
    <figure class="card trendy-card" aria-label="Trendy card">
        <div class="card__img-wrapper w-full" aria-label="Trendy card image">
            <ParallaxCard :with-glare="false" class="cursor-move">
                <AppImage
                    :src="plant.image_url"
                    :alt="plant.title"
                    class="parallax-image filte h-full w-full object-contain drop-shadow-md transition-transform duration-500 hover:scale-110 md:drop-shadow-2xl"
                />
            </ParallaxCard>
        </div>
        <div class="card__info" aria-label="Card information">
            <h3 class="card__title card__title--white" aria-label="Slider card title">
                <Link
                    class="card__link"
                    :href="route('shop.show', plant.id)"
                    :aria-label="plant.title"
                >
                    {{ plant.title }}
                </Link>
            </h3>

            <p class="card__descr card__descr--white line-clamp-5">
                {{ plant.description }}
            </p>

            <div class="card__price" aria-label="Plant price">{{ plant.price }} $</div>

            <div class="card__buttons" aria-label="Buttons">
                <AppExploreButton
                    :href="route('shop.show', plant.id)"
                    aria-label="Explore"
                    class="button--rect card__button"
                >
                    Explore
                </AppExploreButton>

                <AppBuyButton
                    :is-in-cart="isInCart"
                    :is-processing="isProcessing"
                    :disabled="plant.stock === 0"
                    @click="emit('add-to-cart', plant)"
                    class="button--square"
                    aria-label="Add to cart"
                />
            </div>
        </div>
    </figure>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .trendy-card {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        max-width: unset;
        width: 100%;
        aspect-ratio: unset;
        max-height: unset;
        margin-top: 0;
        padding: 1rem 4rem 1rem 0;
        gap: calc(1rem * (60px / b.$basicFontSize));

        @media (max-width: b.$mediaSmallPC) {
            flex-direction: column;
            justify-content: center;
            padding: 0 2rem 4rem;
        }

        @media (max-width: b.$mediaTablet) {
            gap: calc(1rem * (60px / b.$basicFontSize));
        }

        @media (max-width: b.$mediaMobile) {
            gap: calc(1rem * (34px / b.$basicFontSize));

            padding: 0 2rem 2rem;
        }

        &::before {
            content: '';
            filter: none;
            background: url('/resources/images/hero-bigCard_bg.png') center / 100% 100% no-repeat;
            border-radius: calc(1rem * (24px / b.$basicFontSize));
            backdrop-filter: blur(5px);
            overflow: hidden;

            @media (max-width: b.$mediaSmallPC) {
                border-radius: 0rem;
                background: url('/resources/images/hero-bigCard_bg-mobile.png') center / 100% 100%
                    no-repeat;
                border-radius: 9vw;
            }
        }

        .card__info {
            @include b.flex(flex-start, flex-start, column);

            gap: calc(1rem * (24px / b.$basicFontSize));

            @media (max-width: b.$mediaTablet) {
                margin-left: 0;
                flex-basis: 65%;
            }

            @media (max-width: b.$mediaMobile) {
                flex-basis: unset;
            }
        }

        .card__img-wrapper {
            max-width: unset;
            position: relative;
            top: unset;
            left: unset;
            translate: unset;
            height: auto;
            max-width: 500px;
            flex: 1 0 50%;
            margin: 0;
            aspect-ratio: unset;
            overflow: hidden;

            @media (max-width: 1154px) {
                flex: 0 0 100%;
                margin: 0 auto;
                max-width: 400px;
                bottom: auto;
            }

            @media (max-width: b.$mediaTablet) {
                max-width: 300px;
                position: static;
            }

            @media (max-width: b.$mediaMobile) {
                margin-top: -30%;
                max-width: 280px;
            }

            img {
                object-fit: contain;
                width: 100%;
                height: auto;
            }
        }

        .card__title {
            padding: 0;
            margin: 0;
            white-space: wrap;
        }

        .card__descr {
            font-size: calc(1rem * (20px / b.$basicFontSize));
            padding: 0;
        }

        .card__price {
            padding: 0;
        }

        .card__buttons {
            @media (max-width: b.$mediaMobile) {
                gap: 1rem;
            }
        }

        .card__button {
            margin: 0;
        }
    }

    .trendy-card:nth-child(2) {
        padding: 1rem 0 1rem 4rem;

        @media (max-width: b.$mediaSmallPC) {
            padding: 0 2rem 4rem;
        }

        .card__img-wrapper {
            order: 2;

            @media (max-width: b.$mediaSmallPC) {
                order: 1;
            }
        }

        .card__info {
            order: 1;
        }
    }

    .parallax-image {
        transform: rotateX(calc(var(--my) * -6deg)) rotateY(calc(var(--mx) * 6deg))
            translateX(calc(var(--mx) * -10px)) translateY(calc(var(--my) * -10px)) scale(1.1);

        transition: transform 0.3s ease-out;
        will-change: transform;
    }
</style>
