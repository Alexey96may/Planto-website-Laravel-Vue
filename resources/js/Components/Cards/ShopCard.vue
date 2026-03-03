<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    import { usePage } from '@inertiajs/vue3';

    import AppBuyButton from '@/Components/UI/AppBuyButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import { Product, SharedData } from '@/types';
    import { formatUSD } from '@/utils/money';

    interface Props {
        plant?: Product;
        current_page?: string | number;
        isInCart?: boolean;
        isProcessing?: boolean;
    }

    const {
        plant = {} as Product,
        current_page = '',
        isInCart = false,
        isProcessing = false,
    } = defineProps<Props>();

    const page = usePage<SharedData>();

    const addToCart = () => {
        const id = plant?.id;

        router.post(route('cart.add'), {
            product_id: id,
            quantity: 1,
        });
    };

    const emit = defineEmits<{
        (e: 'add-to-cart', product: Product): void;
    }>();
</script>

<template>
    <figure
        class="product-card group relative p-5 transition-all duration-300 max-w-60 border border-current rounded-xl bg-teal-700/10 hover:bg-teal-700/30"
        aria-label="Top card"
    >
        <div class="image-wrapper mb-8 aspect-square w-full overflow-hidden">
            <AppImage
                :src="plant.image_url"
                :alt="plant.title"
                class="plant-image mx-auto h-full w-full object-contain transition-transform duration-300 group-hover:scale-105"
            ></AppImage>
        </div>

        <div class="product-info w-full text-left">
            <h3
                class="text-white text-lg font-medium mb-1.5 text-nowrap text-ellipsis whitespace-nowrap break-all overflow-hidden"
                aria-label="Card title"
                :title="plant.title"
            >
                <Link
                    :href="'/shop/plant-' + plant.id"
                    :data="{ page: current_page }"
                    class="card__tag-title absolute inset-0"
                    aria-label="To {{ plant.title }}"
                >
                </Link>
                {{ plant.title }}
            </h3>

            <p class="text-zinc-400 text-xs mb-4 line-clamp-2" aria-label="Plant description">
                {{ plant.description }}
            </p>

            <div
                class="flex items-center justify-between mt-auto gap-2"
                aria-label="Plant buying info"
            >
                <p class="text-white text-sd font-bold" aria-label="Plant price">
                    {{ formatUSD(plant.price) }}
                </p>

                <AppBuyButton
                    :is-in-cart="isInCart"
                    :is-processing="isProcessing"
                    :disabled="plant.stock === 0"
                    @click="emit('add-to-cart', plant)"
                    class="button--square z-[1]"
                    aria-label="Add to cart"
                />
            </div>
        </div>
    </figure>
</template>
