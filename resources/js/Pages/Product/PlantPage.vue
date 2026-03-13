<script setup lang="ts">
    import { computed, ref, watch } from 'vue';

    import { Head, Link, router } from '@inertiajs/vue3';

    import {
        ChevronLeftIcon,
        MinusIcon,
        PlusIcon,
        ShoppingBagIcon,
        TrashIcon,
        XMarkIcon,
    } from '@heroicons/vue/24/outline';
    import { route } from 'ziggy-js';

    import AppImage from '@/Components/UI/AppImage.vue';
    import Modal from '@/Components/UI/Modal.vue';
    import ParallaxCard from '@/Components/UI/ParallaxCard.vue';
    import WindEffect from '@/Components/UI/WindEffect.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useFlash } from '@/composables/useFlash';
    import { CartItems, ProductWithCategory } from '@/types';
    import { calculateTotal, formatUSD } from '@/utils/money';

    defineOptions({ layout: MainLayout });

    const props = defineProps<{
        product: ProductWithCategory;
        backUrl: string;
        cart_items: CartItems;
    }>();

    const count = ref(props.cart_items[props.product.id] || 1);
    const totalPriceRaw = computed(() => calculateTotal(props.product.price, count.value));
    const formattedPrice = computed(() => formatUSD(totalPriceRaw.value));

    const isZoomed = ref(false);
    const openModal = () => (isZoomed.value = true);
    const closeModal = () => (isZoomed.value = false);

    watch(
        () => props.cart_items[props.product.id],
        (newVal) => {
            if (newVal) count.value = newVal;
        },
    );

    const add = () => {
        if (count.value >= props.product.stock) return;
        count.value++;
    };

    const remove = () => {
        if (count.value > 1) count.value--;
    };

    const addOrUpdateCart = () => {
        const id = props.product?.id;
        const isInCart = !!props.cart_items[id];

        if (isInCart) {
            router.patch(
                route('cart.update', id),
                { quantity: count.value },
                { preserveScroll: true },
            );
        } else {
            router.post(
                route('cart.add'),
                { product_id: id, quantity: count.value },
                { preserveScroll: true },
            );
        }
    };

    const buttonText = computed(() =>
        props.cart_items[props.product.id] ? 'Update Quantity' : 'Add to Cart',
    );

    const { notifyWithUndo } = useFlash();

    let isDeleting = ref<number | null>(null);

    const removeFromCart = async (): Promise<void> => {
        if (isDeleting.value === props.product.id) return;

        isDeleting.value = props.product.id;

        try {
            const confirmed = await notifyWithUndo('Purging product from cart...', 5000);

            if (confirmed) {
                router.delete(route('cart.remove', props.product.id), {
                    preserveScroll: true,
                    onFinish: () => {
                        isDeleting.value = null;
                    },
                    onError: () => {
                        isDeleting.value = null;
                    },
                });
            } else {
                isDeleting.value = null;
            }
        } catch (e) {
            isDeleting.value = null;
        }
    };
</script>

<template>
    <Head :title="product.title" />

    <div class="w-full bg-plant-shop">
        <div class="mx-auto max-w-6xl px-6 py-10 lg:py-16">
            <WindEffect :particleCount="30" :windStrength="1" />

            <Link
                :href="backUrl"
                class="group relative z-[3] inline-flex items-center gap-2 text-sm font-medium text-zinc-500 transition-colors hover:text-emerald-500"
            >
                <ChevronLeftIcon class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
                Back to Catalog
            </Link>

            <div
                class="relative z-[3] mt-8 grid gap-12 rounded-[1rem] border border-emerald-400/50 bg-plant-green px-4 py-8 lg:grid-cols-2 lg:items-center lg:rounded-[2rem] lg:px-6 lg:py-12"
            >
                <ParallaxCard
                    @click="openModal"
                    class="aspect-square cursor-pointer rounded-[2.5rem] border border-zinc-800 bg-zinc-900/50 shadow-2xl"
                >
                    <AppImage
                        :src="product?.image_url"
                        :alt="product.title"
                        class="parallax-image h-full w-full object-cover"
                    />

                    <div
                        v-if="product.stock < 5 && product.stock > 0"
                        class="absolute right-6 top-6 z-20 rounded-full border border-orange-500/20 bg-orange-500/10 px-4 py-1.5 text-[0.5rem] font-bold uppercase tracking-widest text-orange-500 backdrop-blur-md md:text-xs"
                    >
                        Only {{ product.stock }} left
                    </div>
                </ParallaxCard>

                <div class="flex flex-col">
                    <div class="mb-6">
                        <h1
                            class="mt-2 text-3xl font-bold tracking-tight text-zinc-100 lg:text-5xl"
                        >
                            {{ product.title }}
                        </h1>
                        <Link
                            v-if="product.category"
                            :href="route('shop', { category: product?.category?.slug })"
                            class="mt-4 block text-xs font-bold uppercase tracking-[0.2em] text-emerald-600 hover:underline"
                        >
                            {{ product?.category?.title }}
                        </Link>
                        <p class="mt-6 text-3xl font-light text-emerald-300">
                            {{ formatUSD(product.price) }}
                        </p>
                    </div>

                    <div class="mb-8 border-t border-zinc-800 pt-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-zinc-500">
                            Description
                        </h3>
                        <p class="mt-4 text-lg leading-relaxed text-zinc-400">
                            {{
                                product.description ||
                                'Our master gardeners are still writing a perfect description for this green friend.'
                            }}
                        </p>
                    </div>

                    <div v-if="product.stock" class="space-y-6">
                        <div
                            class="flex flex-wrap items-center justify-center gap-6 lg:justify-start"
                        >
                            <div
                                class="flex items-center rounded-2xl border border-zinc-800 bg-zinc-900 p-1"
                            >
                                <button
                                    @click="remove"
                                    :disabled="count <= 1"
                                    class="flex h-12 w-12 items-center justify-center rounded-xl text-zinc-400 transition-all hover:bg-zinc-800 hover:text-white disabled:opacity-20"
                                >
                                    <MinusIcon class="h-5 w-5" />
                                </button>
                                <span class="w-12 text-center text-xl font-bold text-zinc-200">{{
                                    count
                                }}</span>
                                <button
                                    @click="add"
                                    :disabled="count >= product.stock"
                                    class="flex h-12 w-12 items-center justify-center rounded-xl text-zinc-400 transition-all hover:bg-zinc-800 hover:text-white disabled:opacity-20"
                                >
                                    <PlusIcon class="h-5 w-5" />
                                </button>
                            </div>

                            <button
                                @click="addOrUpdateCart"
                                class="group flex flex-grow items-center justify-center gap-3 rounded-2xl bg-emerald-700 px-8 py-4 font-bold text-white transition-all hover:bg-emerald-600 hover:shadow-[0_0_15px_rgba(16,185,129,0.2)] active:scale-[0.98]"
                            >
                                <ShoppingBagIcon class="h-6 w-6" />
                                {{ buttonText }} — {{ formattedPrice }}
                            </button>
                        </div>

                        <button
                            v-if="cart_items[product.id]"
                            @click="removeFromCart"
                            class="flex items-center gap-2 text-sm font-bold text-zinc-500 transition-colors hover:text-red-500"
                        >
                            <TrashIcon class="h-4 w-4" />
                            Remove from cart
                        </button>
                    </div>

                    <div
                        v-else
                        class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-6 text-center"
                    >
                        <p class="text-xl font-medium text-emerald-600">
                            Temporarily out of stock! 🌿
                        </p>
                        <p class="mt-1 text-pretty text-sm text-zinc-500">
                            We're growing more. Check back in a few days!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Modal :show="isZoomed" @close="closeModal" maxWidth="xl">
        <div class="relative flex items-center justify-center overflow-hidden bg-plant-shop p-1">
            <button
                @click="closeModal"
                class="absolute right-4 top-4 z-50 rounded-full border bg-orange-700/80 p-2 text-orange-200 transition-colors hover:bg-orange-900/80"
            >
                <XMarkIcon class="h-6 w-6" />
            </button>

            <img
                :src="product?.image_url"
                class="w-auto rounded-md object-contain"
                :alt="product.title"
            />
        </div>
    </Modal>
</template>

<style scoped>
    .parallax-container {
        perspective: 1200px;
        transform-style: preserve-3d;
    }

    .parallax-image {
        transform: rotateX(calc(var(--my) * -6deg)) rotateY(calc(var(--mx) * 6deg))
            translateX(calc(var(--mx) * -10px)) translateY(calc(var(--my) * -10px)) scale(1.1);

        transition: transform 0.1s ease-out;
        will-change: transform;
    }

    .parallax-glare {
        width: 150%;
        height: 150%;
        top: -25%;
        left: -25%;

        transform: translateX(calc(var(--mx) * 10%)) translateY(calc(var(--my) * 10%));

        filter: blur(40px);
    }

    .parallax-container:not(:hover) .parallax-image {
        transition: transform 0.6s cubic-bezier(0.2, 0, 0.3, 1);
        transform: rotateX(0deg) rotateY(0deg) translateX(0) translateY(0) scale(1);
    }
</style>
