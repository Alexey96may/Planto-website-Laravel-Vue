<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import IconBag from 'img/icons/bag.svg?component';
    import { route } from 'ziggy-js';

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

    const handleAddToCart = () => {
        if (!isInCart && plant.stock > 0) {
            emit('add-to-cart', plant);
        }
    };
</script>

<template>
    <figure class="card trendy-card" aria-label="Trendy card">
        <div class="card__img-wrapper" aria-label="Trendy card image">
            <AppImage :src="plant.image_url" :alt="plant.title" />
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
                <button class="button--rect" aria-label="Explore">
                    <Link
                        class="card__link"
                        :href="route('shop.show', plant.id)"
                        :aria-label="plant.title"
                    >
                        Explore
                    </Link>
                </button>

                <button
                    @click="handleAddToCart"
                    class="button--square"
                    aria-label="Add to cart"
                    :disabled="plant.stock === 0 || isInCart || isProcessing"
                >
                    <IconBag
                        class="button-image"
                        aria-label="Cart"
                        :class="{ 'text-green-500': isInCart }"
                    ></IconBag>
                </button>
            </div>
        </div>
    </figure>
</template>
