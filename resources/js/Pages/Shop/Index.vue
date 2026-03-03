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
</script>

<template>
    <Head title="Plant Catalog" />

    <div class="w-full bg-plant-shop pt-8 pb-10">
        <div class="max-w-7xl mx-auto px-4">
            <p class="text-zinc-500 font-extralight pb-4 text-xs whitespace-nowrap text-right px-4">
                Showing: <span class="text-zinc-200">{{ products.total }} products</span>
            </p>
            <div class="flex flex-col xl:flex-row gap-2">
                <aside
                    class="w-full xl:w-64 px-4 py-6 shadow-sm shadow-emerald-400/50 rounded-lg bg-plant-green"
                >
                    <SearchInput v-model="search" @apply-search="debouncedApplyFilters" />

                    <PriceFilter
                        class="mb-10"
                        v-model:min="minPrice"
                        v-model:max="maxPrice"
                        @change="debouncedApplyFilters"
                    />

                    <div
                        class="mb-10 flex items-center justify-between group cursor-pointer"
                        @click="
                            inStockOnly = !inStockOnly;
                            debouncedApplyFilters();
                        "
                    >
                        <span
                            class="text-sm font-medium text-zinc-300 group-hover:text-emerald-600 transition-colors"
                        >
                            In Stock Only
                        </span>

                        <div
                            class="relative w-10 h-5 transition-colors duration-200 ease-in-out rounded-full"
                            :class="inStockOnly ? 'bg-emerald-600' : 'bg-zinc-600'"
                        >
                            <div
                                class="absolute top-1 left-1 w-3 h-3 transition-transform duration-200 ease-in-out transform bg-white rounded-full"
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
                    class="flex-grow bg-plant-green px-4 py-6 shadow-sm shadow-emerald-400/50 rounded-lg"
                >
                    <div class="flex justify-between items-end mb-8">
                        <SortFilter v-model="sort" @change="debouncedApplyFilters" />

                        <div>
                            <Link
                                :href="route('shop')"
                                :preserve-scroll="true"
                                class="group overflow-hidden flex items-center justify-center gap-2 w-full px-3 py-2 text-sm rounded-xl border border-zinc-600 text-zinc-300 hover:bg-orange-800/10 transition-all duration-300"
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
                                    class="text-xs font-bold uppercase tracking-widest text-ellipsis whitespace-nowrap"
                                >
                                    Reset
                                </span>
                            </Link>
                        </div>
                    </div>

                    <div
                        v-if="products.data.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 justify-center gap-4 sm:gap-8"
                    >
                        <PlantCard
                            v-for="plant in products.data"
                            :key="plant.id"
                            :plant="plant"
                            :current_page="products.current_page"
                            :processing-id="processingId === plant.id"
                            :is-in-cart="cartIds.includes(plant.id)"
                            @add-to-cart="handleAddToCart"
                        />
                    </div>

                    <div v-else class="text-center py-20 bg-gray-50 rounded-3xl">
                        <p class="text-xl text-gray-400">
                            There are no products in this category yet...
                        </p>
                        <Link
                            :href="route('shop')"
                            class="text-green-600 underline mt-4 inline-block"
                            >Return to all products</Link
                        >
                    </div>
                </main>
            </div>
        </div>
        <Pagination :links="products.links" />
    </div>
</template>
