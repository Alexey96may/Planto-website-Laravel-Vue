<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Link, router } from '@inertiajs/vue3';

    import { ShoppingCartIcon } from '@heroicons/vue/24/outline';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useFlash } from '@/composables/useFlash';
    import { useSound } from '@/composables/useSound';
    import { CartData, CartItem, Seo } from '@/types';
    import { debounce } from '@/utils/helpers';
    import { formatUSD } from '@/utils/money';

    const { playCancel, playClick, playSlideMove } = useSound();

    const props = defineProps<{
        cart: CartData;
        seo?: Seo;
    }>();

    defineOptions({
        layout: MainLayout,
    });

    const { notifyWithUndo, notify } = useFlash();

    let deletingIds = ref<Set<number>>(new Set());
    const isUpdating = ref<number | null>(null);

    const removeFromCart = async (productId: number): Promise<void> => {
        if (deletingIds.value.has(productId)) return;
        deletingIds.value.add(productId);

        try {
            const confirmed = await notifyWithUndo('Purging product from cart...', 5000);

            if (confirmed) {
                router.delete(route('cart.remove', productId), {
                    preserveScroll: true,
                    onFinish: () => {
                        deletingIds.value.delete(productId);
                    },
                    onSuccess: () => {
                        playSlideMove();
                    },
                    onError: () => {
                        deletingIds.value.delete(productId);
                        playCancel();
                    },
                });
            } else {
                deletingIds.value.delete(productId);
            }
        } catch (e) {
            deletingIds.value.delete(productId);
        }
    };

    const updateRequests = ref<Record<number, ReturnType<typeof debounce>>>({});

    const updateQuantity = (item: CartItem, newQuantity: number) => {
        if (newQuantity < 1) return;

        item.quantity = newQuantity;

        isUpdating.value = item.product_id;

        if (!updateRequests.value[item.product_id]) {
            updateRequests.value[item.product_id] = debounce((id: number, qty: number) => {
                router.patch(
                    route('cart.update', id),
                    { quantity: qty },
                    {
                        preserveScroll: true,
                        onFinish: () => {
                            isUpdating.value = null;
                        },
                    },
                );
            }, 500);
        }

        updateRequests.value[item.product_id](item.product_id, newQuantity);
    };

    const hasAvailableItems = computed(() => {
        return props.cart.items.some((item) => item.is_available);
    });

    const hasStockIssues = computed(() => {
        return props.cart.items.some((item) => item.quantity > item.stock);
    });

    const canCheckout = computed(() => {
        return hasAvailableItems.value && !hasStockIssues.value;
    });
</script>

<template>
    <SeoMeta :seo="seo" />

    <div class="mx-auto max-w-5xl bg-plant-shop px-6 py-16 lg:py-24">
        <header class="mb-12">
            <h1 id="cart-title" class="text-4xl font-bold tracking-tight text-zinc-200 lg:text-6xl">
                Your <span class="text-emerald-600">Cart</span>
            </h1>
            <p v-if="cart.items.length > 0" class="mt-4 text-zinc-500" role="status">
                You have {{ cart.items.length }} plant{{ cart.items.length > 1 ? 's' : '' }} ready
                to ship.
            </p>
        </header>

        <transition name="fade-slide" mode="out-in">
            <div v-if="cart.items.length > 0" class="grid gap-8 lg:grid-cols-3" role="main">
                <div class="space-y-4 lg:col-span-2">
                    <transition-group
                        tag="div"
                        class="relative space-y-4"
                        enter-active-class="transition duration-500 ease-out"
                        enter-from-class="transform translate-x-4 opacity-0"
                        leave-active-class="transition duration-300 ease-in"
                        leave-to-class="transform -translate-x-10 opacity-0"
                        move-class="transition duration-500"
                    >
                        <article
                            v-for="item in cart.items"
                            :key="item.product_id"
                            class="group relative flex items-center gap-4 rounded-[1.5rem] border border-zinc-800 bg-zinc-900/50 p-4 transition-all duration-300 hover:border-zinc-700 sm:gap-6 sm:p-6"
                            :class="[
                                deletingIds.has(item.product_id)
                                    ? 'pointer-events-none scale-95 opacity-50'
                                    : '',
                                item.quantity > item.stock
                                    ? 'border-orange-500/50 bg-orange-500/5 shadow-[0_0_20px_rgba(249,115,22,0.1)]'
                                    : 'border-zinc-800 bg-zinc-900/50 hover:border-zinc-700',
                            ]"
                            :aria-busy="isUpdating === item.product_id"
                        >
                            <div
                                class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl bg-zinc-800 sm:h-32 sm:w-32"
                            >
                                <AppImage
                                    :src="item.image"
                                    :alt="item.title"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                />
                            </div>

                            <div class="flex flex-grow flex-col">
                                <div class="flex justify-between gap-2">
                                    <h3 class="text-md font-bold text-zinc-200 sm:text-xl">
                                        <Link
                                            :href="'/shop/plant-' + item.product_id"
                                            :aria-label="'View ' + item.title"
                                        >
                                            {{ item.title }}
                                        </Link>
                                    </h3>
                                    <button
                                        @click="removeFromCart(item.product_id)"
                                        @mousedown="playCancel"
                                        class="rounded-lg text-zinc-500 transition-colors hover:text-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-400"
                                        :aria-label="'Remove ' + item.title + ' from cart'"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            aria-hidden="true"
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

                                <div class="mt-2 flex items-center gap-3">
                                    <p class="text-sm font-medium text-emerald-600/80">
                                        {{ formatUSD(item.price) }}
                                    </p>

                                    <span
                                        v-if="item.quantity > item.stock && item.stock > 0"
                                        class="animate-pulse text-[10px] font-bold uppercase tracking-widest text-orange-500"
                                    >
                                        Only {{ item.stock }} left in stock!
                                    </span>
                                </div>

                                <div class="mt-auto pt-4">
                                    <div v-if="item.stock" class="flex items-center gap-4">
                                        <div
                                            class="flex items-center rounded-xl bg-zinc-800 p-1 ring-1 ring-zinc-700"
                                        >
                                            <button
                                                @click="updateQuantity(item, item.quantity - 1)"
                                                @mousedown="playClick"
                                                :disabled="item.quantity <= 1"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-700 hover:text-white focus:outline-none focus-visible:ring-1 focus-visible:ring-emerald-500 disabled:opacity-20"
                                                :aria-label="'Decrease quantity of ' + item.title"
                                            >
                                                <span aria-hidden="true">-</span>
                                            </button>
                                            <span
                                                class="w-8 text-center text-sm font-bold text-zinc-200"
                                                :class="{
                                                    'animate-pulse opacity-50':
                                                        isUpdating === item.product_id,
                                                }"
                                                aria-live="polite"
                                                :aria-label="'Quantity: ' + item.quantity"
                                            >
                                                {{ item.quantity }}
                                            </span>
                                            <button
                                                @click="updateQuantity(item, item.quantity + 1)"
                                                @mousedown="playClick"
                                                :disabled="item.quantity >= item.stock"
                                                class="flex h-8 w-8 items-center justify-center rounded-lg text-zinc-400 transition-colors hover:bg-zinc-700 hover:text-white focus:outline-none focus-visible:ring-1 focus-visible:ring-emerald-500 disabled:opacity-20"
                                                :aria-label="'Increase quantity of ' + item.title"
                                            >
                                                <span aria-hidden="true">+</span>
                                            </button>
                                        </div>
                                        <span
                                            class="hidden text-right font-bold text-zinc-200 sm:block"
                                        >
                                            <span class="sr-only">Subtotal for this item: </span>
                                            {{ formatUSD(item.total_price) }}
                                        </span>
                                    </div>
                                    <div
                                        v-else
                                        class="text-sm font-medium text-red-400/80"
                                        role="alert"
                                    >
                                        Temporarily out of stock! 🌿
                                    </div>
                                </div>
                            </div>
                        </article>
                    </transition-group>
                </div>

                <aside class="lg:col-span-1" aria-labelledby="summary-title">
                    <transition
                        appear
                        enter-active-class="transition duration-700 delay-300 ease-out"
                        enter-from-class="transform translate-y-8 opacity-0"
                        enter-to-class="transform translate-y-0 opacity-100"
                    >
                        <div
                            class="sticky top-6 rounded-[2rem] border border-emerald-900/30 bg-emerald-950/20 p-8 backdrop-blur-sm"
                        >
                            <h2 id="summary-title" class="mb-6 text-2xl font-bold text-zinc-200">
                                Order Summary
                            </h2>

                            <div class="space-y-4 border-b border-zinc-500 pb-6">
                                <div class="flex justify-between gap-2 text-zinc-400">
                                    <span>Subtotal</span>
                                    <span aria-live="polite">{{ formatUSD(cart.total_sum) }}</span>
                                </div>
                                <div class="flex justify-between gap-2 text-zinc-400">
                                    <span>Shipping</span>
                                    <span
                                        class="text-sm font-medium uppercase tracking-widest text-emerald-500"
                                        >Calculated at next step</span
                                    >
                                </div>
                            </div>

                            <div
                                class="mt-6 flex justify-between gap-2 text-xl font-bold text-zinc-100"
                            >
                                <span>Total</span>
                                <span aria-live="polite">{{ formatUSD(cart.total_sum) }}</span>
                            </div>

                            <Link
                                v-if="hasAvailableItems"
                                :href="route('checkout.index')"
                                @mousedown="playClick"
                                class="mt-8 block w-full rounded-[1.2rem] bg-emerald-600 p-4 text-center font-bold text-white transition-all hover:bg-emerald-500 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/50"
                            >
                                Checkout Now
                            </Link>
                        </div>
                    </transition>
                </aside>
            </div>

            <div v-else class="py-26 text-center" role="status">
                <div
                    class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-zinc-900 text-5xl"
                >
                    <ShoppingCartIcon class="h-6 w-6 text-emerald-500" aria-hidden="true" />
                </div>
                <h2 class="mb-4 font-serif text-3xl font-bold text-zinc-200">Your cart is empty</h2>
                <p class="mb-10 text-zinc-500">
                    Looks like you haven't found your perfect plant yet.
                </p>
                <Link
                    :href="route('shop')"
                    class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-8 py-4 font-bold text-white transition-all hover:bg-emerald-500 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/50"
                >
                    Start Shopping
                </Link>
            </div>
        </transition>
    </div>
</template>

<style scoped>
    .fade-slide-enter-active,
    .fade-slide-leave-active {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fade-slide-enter-from {
        opacity: 0;
        transform: translateY(10px) scale(0.98);
    }

    .fade-slide-leave-to {
        opacity: 0;
        transform: translateY(-10px) scale(0.98);
    }

    .list-leave-active {
        position: absolute;
        width: 100%;
    }
</style>
