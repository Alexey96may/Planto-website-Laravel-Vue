<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';

    import AppBuyButton from '@/Components/UI/AppBuyButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import { Product } from '@/types';
    import { formatUSD } from '@/utils/money';

    interface Props {
        plant?: Product;
        current_page?: string | number;
        isInCart?: boolean;
        isProcessing?: boolean;
        isLoading?: boolean;
    }

    const {
        plant = {} as Product,
        current_page = '',
        isInCart = false,
        isProcessing = false,
        isLoading = false,
    } = defineProps<Props>();

    const disabled = computed((): boolean => {
        return plant.stock === 0;
    });

    const emit = defineEmits<{
        (e: 'add-to-cart', product: Product): void;
    }>();

    const allClasses = computed(() => {
        return [
            {
                'hover:bg-teal-700/30': !isProcessing && !isInCart && !disabled.value,
                'opacity-50 bg-teal-700/10 hover:bg-teal-700/50': isProcessing,
                'bg-teal-700/30 hover:bg-teal-700/50': isInCart,
                'opacity-50 bg-orange-700/20 hover:bg-orange-700/50': disabled.value,
            },
        ];
    });
</script>

<template>
    <figure
        class="product-card group relative w-full rounded-xl border border-current p-5 transition-all duration-300 sm:max-w-60"
        :class="[allClasses, { 'is-card-loading': isLoading }]"
        aria-label="Top card"
    >
        <div
            class="image-wrapper mb-8 aspect-square w-full overflow-hidden"
            :class="{ 'skeleton-item': isLoading }"
        >
            <AppImage
                v-if="!isLoading"
                :src="plant.image_url"
                :alt="plant.title"
                class="plant-image mx-auto h-full w-full object-contain transition-transform duration-300 group-hover:scale-105"
            ></AppImage>
        </div>

        <div class="product-info w-full text-left">
            <h3
                class="mb-1.5 overflow-hidden text-ellipsis whitespace-nowrap text-nowrap break-all text-lg font-medium text-white"
                aria-label="Card title"
                :title="plant.title"
                :class="{ 'skeleton-item': isLoading }"
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

            <p
                class="mb-4 line-clamp-2 text-xs text-zinc-400"
                aria-label="Plant description"
                :class="{ 'skeleton-item': isLoading }"
            >
                {{ plant.description }}
            </p>

            <div
                class="mt-auto flex items-center justify-between gap-2"
                aria-label="Plant buying info"
            >
                <p
                    class="text-sd font-bold text-white"
                    aria-label="Plant price"
                    :class="{ 'skeleton-item': isLoading }"
                >
                    {{ isLoading ? '$0.00' : formatUSD(plant.price) }}
                </p>

                <div v-if="isLoading" class="skeleton-item h-10 w-10 rounded-lg"></div>
                <AppBuyButton
                    v-else
                    :is-in-cart="isInCart"
                    :is-processing="isProcessing"
                    :disabled="disabled"
                    @click="emit('add-to-cart', plant)"
                    class="button--square z-[1]"
                    aria-label="Add to cart"
                />
            </div>
        </div>
    </figure>
</template>

<style scoped>
    .is-card-loading {
        pointer-events: none;
    }

    .is-card-loading .skeleton-item {
        background: rgba(255, 255, 255, 0.05);
        color: transparent !important;
        border-color: transparent !important;
        border-radius: 6px;
        position: relative;
        overflow: hidden;
    }

    .is-card-loading .skeleton-item::after {
        content: '';
        position: absolute;
        inset: 0;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        100% {
            transform: translateX(100%);
        }
    }
</style>
