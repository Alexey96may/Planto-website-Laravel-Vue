<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Link, router } from '@inertiajs/vue3';

    import { ShoppingCartIcon } from '@heroicons/vue/24/outline';
    import { route } from 'ziggy-js';

    import AppImage from '@/Components/UI/AppImage.vue';
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
    <Head title="Your Cart" />

    <div class="mx-auto max-w-5xl bg-plant-shop px-6 py-16 lg:py-24">
        <header class="mb-12">
            <h1 class="text-4xl font-bold tracking-tight text-zinc-200 lg:text-6xl">
                Your <span class="text-emerald-600">Cart</span>
            </h1>
            <p v-if="cart.items.length > 0" class="mt-4 text-zinc-500">
                You have {{ cart.items.length }} plant{{ cart.items.length > 1 ? 's' : '' }} ready
                to ship.
            </p>
        </header>

        <div v-if="cart.items.length > 0" class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                <div
                    v-for="item in cart.items"
                    :key="item.product_id"
                    class="group relative flex items-center gap-4 rounded-[1.5rem] border border-zinc-800 bg-zinc-900/50 p-4 transition-all hover:border-zinc-700 sm:gap-6 sm:p-6"
                >
                    <div
                        class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl bg-zinc-800 sm:h-32 sm:w-32"
                    >
                        <AppImage
                            :src="item.image"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                        />
                    </div>

                    <div class="flex flex-grow flex-col">
                        <div class="flex justify-between gap-2">
                            <h3 class="text-md font-bold text-zinc-200 sm:text-xl">
                                <Link :href="'/shop/plant-' + item.product_id">
                                    {{ item.title }}</Link
                                >
                            </h3>
                            <button
                                @click="removeFromCart(item.product_id)"
                                class="text-zinc-500 transition-colors hover:text-red-400"
                                title="Remove item"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        <p class="mt-2 text-sm font-medium text-emerald-600/80">
                            {{ item.price }} $
                        </p>

                        <div class="mt-auto pt-4">
                            <div v-if="item.stock" class="flex items-center gap-4">
                                <div
                                    class="flex items-center rounded-xl bg-zinc-800 p-1 ring-1 ring-zinc-700"
                                >
                                    <button
                                        @click="updateQuantity(item, item.quantity - 1)"
                                        :disabled="item.quantity <= 1"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-700 hover:text-white disabled:opacity-20"
                                    >
                                        -
                                    </button>
                                    <span class="w-8 text-center text-sm font-bold text-zinc-200">{{
                                        item.quantity
                                    }}</span>
                                    <button
                                        @click="updateQuantity(item, item.quantity + 1)"
                                        :disabled="item.quantity >= item.stock"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-700 hover:text-white disabled:opacity-20"
                                    >
                                        +
                                    </button>
                                </div>
                                <span class="hidden text-right font-bold text-zinc-200 sm:block">
                                    {{ item.total_price }} $
                                </span>
                            </div>
                            <div v-else class="text-sm font-medium text-red-400/80">
                                Temporarily out of stock! 🌿
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div
                    class="sticky top-24 rounded-[2rem] border border-emerald-900/30 bg-emerald-950/20 p-8 backdrop-blur-sm"
                >
                    <h2 class="mb-6 text-2xl font-bold text-zinc-200">Order Summary</h2>

                    <div class="space-y-4 border-b border-zinc-500 pb-6">
                        <div class="flex justify-between gap-2 text-zinc-400">
                            <span>Subtotal</span>
                            <span>{{ cart.total_sum }} $</span>
                        </div>
                        <div class="flex justify-between gap-2 text-zinc-400">
                            <span>Shipping</span>
                            <span
                                class="text-sm font-medium uppercase tracking-widest text-emerald-500"
                                >Calculated at next step</span
                            >
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between gap-2 text-xl font-bold text-zinc-100">
                        <span>Total</span>
                        <span>{{ cart.total_sum }} $</span>
                    </div>

                    <Link
                        v-if="hasAvailableItems"
                        :href="route('checkout.index')"
                        class="mt-8 block w-full rounded-[1.2rem] bg-emerald-600 p-4 text-center font-bold text-white transition-all hover:bg-emerald-500 hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] active:scale-[0.98]"
                    >
                        Checkout Now
                    </Link>

                    <div
                        v-else
                        class="mt-8 cursor-not-allowed rounded-[1.2rem] bg-zinc-800 p-4 text-center font-bold text-zinc-500 opacity-50"
                    >
                        Cart is Empty or Items Unavailable
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="py-26 text-center">
            <div
                class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-zinc-900 text-5xl"
            >
                <ShoppingCartIcon class="h-6 w-6 text-emerald-500" />
            </div>
            <h2 class="mb-4 font-serif text-3xl font-bold text-zinc-200">Your cart is empty</h2>
            <p class="mb-10 text-zinc-500">Looks like you haven't found your perfect plant yet.</p>
            <Link
                :href="route('shop')"
                class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-8 py-4 font-bold text-white transition-all hover:bg-emerald-500"
            >
                Start Shopping
            </Link>
        </div>
    </div>
</template>
