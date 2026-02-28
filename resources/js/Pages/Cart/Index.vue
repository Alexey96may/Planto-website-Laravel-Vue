<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Link, router } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AppImage from '@/Components/AppImage.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { CartData, CartItem } from '@/types';
    import { debounce } from '@/utils/helpers';

    const props = defineProps<{
        cart: CartData;
    }>();

    defineOptions({
        layout: MainLayout,
    });

    const removeFromCart = (productId: number) => {
        if (confirm('Are you sure you want to delete this item?')) {
            router.delete(route('cart.remove', productId), {
                preserveScroll: true,
                onSuccess: () => {
                    // Здесь можно добавить уведомление об успехе
                },
            });
        }
    };

    const updateRequests = ref<Record<number, ReturnType<typeof debounce>>>({});

    const updateQuantity = (item: CartItem, newQuantity: number) => {
        if (newQuantity < 1) return;

        if (newQuantity > item.stock) {
            alert("Sorry, we don't have that many plants 🌿");
            return;
        }

        item.quantity = newQuantity;

        if (!updateRequests.value[item.product_id]) {
            updateRequests.value[item.product_id] = debounce((id: number, qty: number) => {
                router.patch(route('cart.update', id), { quantity: qty }, { preserveScroll: true });
            }, 500);
        }

        updateRequests.value[item.product_id](item.product_id, newQuantity);
    };

    const hasAvailableItems = computed(() => {
        return props.cart.items.some((item) => item.is_available);
    });
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Your cart</h1>

        <div v-if="cart.items.length > 0">
            <div
                v-for="item in cart.items"
                :key="item.product_id"
                class="flex justify-between border-b py-4"
            >
                <div class="flex items-center">
                    <img :src="item.image" class="w-16 h-16 object-cover mr-4" />
                    <div>
                        <h3 class="text-gray-500 font-semibold">
                            {{ item.title }}
                        </h3>
                        <p class="text-gray-500">{{ item.price }} $.</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3" v-if="item.stock">
                    <button
                        @click="updateQuantity(item, item.quantity - 1)"
                        :disabled="item.quantity <= 1 || !item.stock"
                        class="px-2 py-1 bg-gray-200 rounded disabled:opacity-50"
                    >
                        -
                    </button>

                    <span class="font-medium">{{ item.quantity }}</span>

                    <button
                        @click="updateQuantity(item, item.quantity + 1)"
                        class="px-2 py-1 bg-gray-200 rounded"
                        :disabled="item.quantity === item.stock"
                    >
                        +
                    </button>
                </div>
                <div class="flex items-center gap-4 mt-8 text-green" v-else>
                    Temporarily out of stock!
                </div>

                <div class="flex flex-col items-end justify-between">
                    <button
                        @click="removeFromCart(item.product_id)"
                        class="text-red-500 hover:text-red-700 text-sm mb-2"
                    >
                        Delete
                    </button>
                    <div class="text-right font-bold" v-if="item.stock">
                        {{ item.total_price }} $
                    </div>
                </div>
            </div>

            <div class="mt-6 text-right text-xl font-bold">Total: {{ cart.total_sum }} $</div>

            <Link
                v-if="hasAvailableItems"
                :href="route('checkout.index')"
                class="mt-4 block text-center bg-black text-white p-3 rounded"
            >
                Place an order
            </Link>

            <div v-else class="btn-primary opacity-50 cursor-not-allowed text-center">
                Add something to cart
            </div>
        </div>

        <div v-else class="text-center py-10">
            <p>The cart is empty</p>
            <Link :href="route('shop')" class="text-blue-500">Return to the store</Link>
        </div>
    </div>
</template>
