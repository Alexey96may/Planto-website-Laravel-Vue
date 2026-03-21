import { ref } from 'vue';

import { router } from '@inertiajs/vue3';

import { route } from 'ziggy-js';

import { useSound } from '@/composables/useSound';
import { Product } from '@/types';

export function useCart() {
    const processingId = ref<number | null>(null);
    const { moneyClick, playCancel } = useSound();

    const addToCart = (product: Product) => {
        router.post(
            route('cart.add'),
            { product_id: product.id, quantity: 1 },
            {
                preserveScroll: true,
                onBefore: () => {
                    processingId.value = product.id;
                },
                onSuccess: () => {
                    moneyClick();
                },
                onError: () => {
                    playCancel();
                },
                onFinish: () => {
                    processingId.value = null;
                },
            },
        );
    };

    return {
        addToCart,
        processingId,
    };
}
