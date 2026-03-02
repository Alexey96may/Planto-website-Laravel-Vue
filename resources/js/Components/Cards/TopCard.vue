<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import AppBuyButton from '@/Components/UI/AppBuyButton.vue';
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
    <figure class="card top__card" aria-label="Top card">
        <div class="card__img-wrapper" aria-label="Top card image">
            <AppImage :src="plant.image_url" :alt="plant.title"></AppImage>
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

        <p class="card__descr card__descr--white" aria-label="Plant description">
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
