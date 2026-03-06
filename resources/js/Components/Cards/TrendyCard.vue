<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import IconBag from 'img/icons/bag.svg?component';
    import { route } from 'ziggy-js';

    import AppBuyButton from '@/Components/UI/AppBuyButton.vue';
    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
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
        <div
            class="card__img-wrapper relative -mt-16 aspect-square w-full px-4"
            aria-label="Trendy card image"
        >
            <AppImage
                :src="plant.image_url"
                :alt="plant.title"
                class="h-full w-full object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-110"
            />
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

            <p class="card__descr card__descr--white">
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

        @media (max-width: b.$mediaTablet) {
            gap: calc(1rem * (60px / b.$basicFontSize));
        }

        @media (max-width: b.$mediaMobile) {
            flex-direction: column;
            justify-content: center;
            gap: calc(1rem * (34px / b.$basicFontSize));
        }

        &::before {
            content: '';
            filter: none;
            background: url('/resources/images/hero-bigCard_bg.png') center / 100% 100% no-repeat;
            border-radius: calc(1rem * (24px / b.$basicFontSize));
            backdrop-filter: blur(5px);
            overflow: hidden;

            @media (max-width: b.$mediaMobile) {
                border-radius: 0rem;
                background: url('/resources/images/hero-bigCard_bg-mobile.png') center / 100% 100%
                    no-repeat;
                border-radius: 9vw;
            }
        }

        .card__info {
            @include b.flex(flex-start, flex-start, column);
            padding: 5rem;
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
            margin-top: 0;
            position: relative;
            top: unset;
            left: unset;
            translate: unset;
            height: auto;
            margin-bottom: 0;
            aspect-ratio: unset;

            @media (max-width: 1154px) {
                bottom: auto;
            }

            @media (max-width: b.$mediaTablet) {
                position: static;
                flex-basis: 35%;
            }

            @media (max-width: b.$mediaMobile) {
                margin-top: -35%;
                flex-basis: unset;
                max-width: 300px;
            }

            img {
                max-width: min(46.5vw, calc(1rem * (728px / b.$basicFontSize)));
                object-fit: contain;
                width: auto;
                height: auto;

                @media (max-width: b.$mediaTablet) {
                    width: 100%;
                }
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

        .card__button {
            margin: 0;
        }
    }

    .trendy-card:nth-child(2) {
        .card__img-wrapper {
            order: 2;
        }

        .card__info {
            order: 1;
        }
    }
</style>
