<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    import { usePage } from '@inertiajs/vue3';

    import AppBuyButton from '@/Components/UI/AppBuyButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import { Product, SharedData } from '@/types';

    interface Props {
        plant?: Product;
        current_page?: string | number;
        isInCart?: boolean;
        isProcessing?: boolean;
    }

    const { plant = {} as Product, current_page = '' } = defineProps<Props>();

    const page = usePage<SharedData>();

    const addToCart = () => {
        const id = plant?.id;

        router.post(route('cart.add'), {
            product_id: id,
            quantity: 1,
        });
    };

    const isInCart = computed(() => {
        return page.props.cart_ids?.includes(plant.id);
    });

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
                :data="{ page: current_page }"
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
