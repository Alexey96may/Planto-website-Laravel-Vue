<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    import { usePage } from '@inertiajs/vue3';

    import IconBag from 'img/icons/bag.svg?component';

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

    const handleAddToCart = () => {
        if (!isInCart && plant.stock > 0) {
            emit('add-to-cart', plant);
        }
    };
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

            <button
                class="button--square"
                aria-label="Add to cart"
                @click="handleAddToCart"
                :disabled="isInCart || plant.stock === 0"
            >
                <IconBag
                    class="button-image"
                    aria-label="Cart"
                    :class="{ 'text-green-500': isInCart }"
                ></IconBag>
            </button>
        </div>
    </figure>
</template>
