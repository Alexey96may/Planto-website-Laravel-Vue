<script setup lang="ts">
    import { computed, ref, watch } from 'vue';

    import { Head, Link, router, usePage } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import PlantCard from '@/Components/Cards/ShopCard.vue';
    import ShopCategory from '@/Components/Shared/CategoryFilter.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import PriceFilter from '@/Components/Shared/PriceFilter.vue';
    import SearchInput from '@/Components/Shared/SearchFilter.vue';
    import SortFilter from '@/Components/Shared/SortFilter.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import {
        Category,
        PaginatedResponse,
        Product,
        ProductFilters,
        SharedData,
        SortOption,
    } from '@/types';
    import { debounce } from '@/utils/helpers';

    defineOptions({
        layout: MainLayout,
    });

    const props = defineProps<{
        products: PaginatedResponse<Product>;
        categories: Category[];
        currentCategory: string | null;
        filters: ProductFilters;
    }>();

    const page = usePage<SharedData>();
    const cartIds = computed(() => page.props.cart_ids || []);

    const search = ref<string>(props.filters.search || '');
    const minPrice = ref<number | string>(props.filters.min_price || '');
    const maxPrice = ref<number | string>(props.filters.max_price || '');
    const sort = ref<SortOption | string>(props.filters?.sort || 'popular');
    const inStockOnly = ref<boolean>(props.filters.in_stock !== 'false');

    const currentCategory = ref<string | null>(props.currentCategory);

    const isFiltering = ref(false);

    const applyFilters = (): void => {
        const params = {
            category: currentCategory.value,
            search: search.value || undefined,
            min_price: minPrice.value || undefined,
            max_price: maxPrice.value || undefined,
            sort: sort.value,
            in_stock: inStockOnly.value ? 'true' : 'false',
        };

        router.get(route('shop'), params, {
            preserveState: true,
            preserveScroll: true,
            replace: true,

            onBefore: () => {
                isFiltering.value = true;
            },

            onFinish: () => {
                isFiltering.value = false;
            },
        });
    };

    const debouncedApplyFilters = debounce(() => {
        applyFilters();
    }, 500);

    watch(search, () => {
        debouncedApplyFilters();
    });

    const processingId = ref<number | null>(null);

    const handleAddToCart = (product: Product) => {
        router.post(
            route('cart.add'),
            { product_id: product.id, quantity: 1 },
            {
                preserveScroll: true,
                onBefore: () => {
                    processingId.value = product.id;
                },
                onFinish: () => {
                    processingId.value = null;
                },
            },
        );
    };

    const handleChangeCategory = (categotySlug: string | null) => {
        currentCategory.value = categotySlug;

        debouncedApplyFilters();
    };

    const resetFilters = () => {
        search.value = '';
        minPrice.value = '';
        maxPrice.value = '';
        currentCategory.value = null;
        sort.value = 'popular';
        inStockOnly.value = true;

        debouncedApplyFilters();
    };

    watch(
        () => props.currentCategory,
        (newVal) => {
            currentCategory.value = newVal;
        },
    );

    router.on('start', (event) => {
        if (event.detail.visit.url.pathname.includes('/shop')) {
            isFiltering.value = true;
        }
    });

    router.on('finish', () => {
        setTimeout(() => {
            isFiltering.value = false;
        }, 100);
    });
</script>

<template>
    <Head title="Plant Catalog" />

    <div class="w-full bg-plant-shop pb-10 pt-8">
        <div class="mx-auto max-w-7xl px-4">
            <p class="whitespace-nowrap px-4 pb-4 text-right text-xs font-extralight text-zinc-500">
                Showing: <span class="text-zinc-200">{{ products.total }} products</span>
            </p>
            <div class="flex flex-col gap-2 xl:flex-row">
                <aside
                    class="w-full rounded-lg bg-plant-green px-4 py-6 shadow-sm shadow-emerald-400/50 transition-all duration-500 xl:w-64"
                    :class="{ 'pointer-events-none opacity-50 blur-[2px]': isFiltering }"
                >
                    <SearchInput v-model="search" @apply-search="debouncedApplyFilters" />

                    <PriceFilter
                        class="mb-10"
                        v-model:min="minPrice"
                        v-model:max="maxPrice"
                        @change="debouncedApplyFilters"
                    />

                    <div
                        class="group mb-10 flex cursor-pointer items-center justify-between"
                        @click="
                            inStockOnly = !inStockOnly;
                            debouncedApplyFilters();
                        "
                    >
                        <span
                            class="text-sm font-medium text-zinc-300 transition-colors group-hover:text-emerald-600"
                        >
                            In Stock Only
                        </span>

                        <div
                            class="relative h-5 w-10 rounded-full transition-colors duration-200 ease-in-out"
                            :class="inStockOnly ? 'bg-emerald-600' : 'bg-zinc-600'"
                        >
                            <div
                                class="absolute left-1 top-1 h-3 w-3 transform rounded-full bg-white transition-transform duration-200 ease-in-out"
                                :class="inStockOnly ? 'translate-x-5' : 'translate-x-0'"
                            ></div>
                        </div>
                    </div>

                    <ShopCategory
                        @change-category="handleChangeCategory"
                        :categories="categories"
                        :currentCategory="currentCategory"
                    />
                </aside>

                <main
                    class="relative min-h-[500px] flex-grow rounded-lg bg-plant-green px-4 py-6 shadow-sm shadow-emerald-400/50"
                >
                    <transition
                        enter-active-class="transition duration-300 ease-out"
                        leave-active-class="transition duration-200 ease-in"
                        enter-from-class="opacity-0"
                        leave-to-class="opacity-0"
                    >
                        <div
                            v-if="isFiltering"
                            class="absolute inset-0 z-10 flex items-center justify-center rounded-lg bg-plant-green/60 backdrop-blur-[2px]"
                        >
                            <div class="flex flex-col items-center">
                                <div
                                    class="h-12 w-12 animate-spin rounded-full border-4 border-emerald-600/30 border-t-emerald-600"
                                ></div>
                                <span class="mt-4 animate-pulse font-medium text-emerald-500"
                                    >Growing your catalog...</span
                                >
                            </div>
                        </div>
                    </transition>

                    <div class="mb-8 flex items-end justify-between">
                        <SortFilter v-model="sort" @change="debouncedApplyFilters" />

                        <div>
                            <button
                                @click="resetFilters"
                                class="group flex w-full items-center justify-center gap-2 overflow-hidden rounded-xl border border-zinc-600 px-3 py-2 text-sm text-zinc-300 transition-all duration-300 hover:bg-orange-800/10"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform duration-500 group-hover:rotate-[-180deg]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>

                                <span
                                    class="text-ellipsis whitespace-nowrap text-xs font-bold uppercase tracking-widest"
                                >
                                    Reset
                                </span>
                            </button>
                        </div>
                    </div>

                    <div
                        v-if="products.data.length > 0"
                        :class="{ 'opacity-50 transition-opacity duration-300': isFiltering }"
                    >
                        <transition-group
                            tag="div"
                            name="staggered-fade"
                            class="relative grid grid-cols-1 justify-center justify-items-center gap-4 sm:grid-cols-2 sm:gap-8 lg:grid-cols-3"
                        >
                            <PlantCard
                                v-for="(plant, index) in products.data"
                                :key="plant.id"
                                :plant="plant"
                                :style="{ '--index': index }"
                                :current_page="products.current_page"
                                :processing-id="processingId === plant.id"
                                :is-in-cart="cartIds.includes(plant.id)"
                                @add-to-cart="handleAddToCart"
                            />
                        </transition-group>
                    </div>

                    <div v-else class="rounded-3xl bg-plant-shop py-20 text-center">
                        <p class="text-xl text-gray-300">
                            There are no products in this category yet...
                        </p>
                        <button
                            @click="resetFilters"
                            class="mt-4 inline-block text-emerald-600 underline transition duration-300 hover:text-emerald-800"
                        >
                            Return to all products
                        </button>
                    </div>
                </main>
            </div>
        </div>
        <Pagination :links="products.links" />
    </div>
</template>

<style scoped>
    .staggered-fade-move {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .staggered-fade-enter-active {
        transition: all 0.5s ease-out;
        transition-delay: calc(var(--index) * 0.05s);
    }

    .staggered-fade-leave-active {
        transition: all 0.3s ease-in;
        position: absolute;
        z-index: 0;
    }

    .staggered-fade-enter-from {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }

    .staggered-fade-leave-to {
        opacity: 0;
        transform: scale(0.8);
    }

    .staggered-fade-leave-active {
        max-width: 300px;
    }

    .fade-layout-enter-active,
    .fade-layout-leave-active {
        transition:
            opacity 0.4s ease,
            transform 0.4s ease;
    }

    .fade-layout-enter-from {
        opacity: 0;
        transform: translateY(10px);
    }

    .fade-layout-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>
