<script setup>
import { Head, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import PlantCard from "@/Components/TopCard.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

defineOptions({
    layout: (h, page) =>
        h(
            MainLayout,
            {
                full: false,
            },
            () => page,
        ),
});

const props = defineProps({
    products: Object,
    categories: Array,
    currentCategory: String,
    filters: Object,
});

const search = ref(props.filters.search || "");
const minPrice = ref(props.filters.min_price || "");
const maxPrice = ref(props.filters.max_price || "");

const applyFilters = () => {
    router.get(
        "/shop",
        {
            category: props.filters.category,
            search: search.value,
            min_price: minPrice.value,
            max_price: maxPrice.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};
</script>

<template>
    <Head title="Каталог цветов" />

    <aside class="w-full md:w-64">
        <div class="mb-6">
            <input
                v-model="search"
                @keyup.enter="applyFilters"
                type="text"
                placeholder="Поиск растений..."
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
            />
        </div>

        <div class="space-y-4">
            <h3 class="font-bold">Цена</h3>
            <div class="flex gap-2">
                <input
                    v-model="minPrice"
                    type="number"
                    placeholder="От"
                    class="w-1/2 border rounded-md p-2 text-sm"
                />
                <input
                    v-model="maxPrice"
                    type="number"
                    placeholder="До"
                    class="w-1/2 border rounded-md p-2 text-sm"
                />
            </div>
            <button
                @click="applyFilters"
                class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition"
            >
                Применить
            </button>
            <Link
                href="/shop"
                class="block text-center text-xs text-gray-400 underline"
                >Сбросить всё</Link
            >
        </div>
    </aside>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row gap-10">
            <aside class="w-full md:w-64 flex-shrink-0">
                <h2 class="text-xl font-bold mb-6">Категории</h2>
                <nav class="space-y-2">
                    <Link
                        href="/shop"
                        :class="[
                            'block px-4 py-2 rounded-lg transition',
                            !currentCategory
                                ? 'bg-green-600 text-white shadow-md'
                                : 'hover:bg-gray-100',
                        ]"
                    >
                        Все растения
                    </Link>

                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('shop', { category: category.slug })"
                        :class="[
                            'block px-4 py-2 rounded-lg transition',
                            currentCategory === category.slug
                                ? 'bg-green-600 text-white shadow-md'
                                : 'hover:bg-gray-100',
                        ]"
                    >
                        {{ category.title }}
                    </Link>
                </nav>

                <div
                    class="mt-10 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-300"
                >
                    <p class="text-sm text-gray-500 italic">
                        Фильтры по цене появятся здесь позже...
                    </p>
                </div>
            </aside>

            <main class="flex-grow">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h1
                            class="text-4xl font-black italic uppercase tracking-tighter"
                        >
                            Наш магазин
                        </h1>
                        <p class="text-gray-500">
                            Найдено товаров: {{ products.total }}
                        </p>
                        <p class="text-gray-500 text-sm">
                            Показано {{ products.from }}-{{ products.to }} из
                            {{ products.total }} растений
                        </p>
                    </div>
                </div>

                <div
                    v-if="products.data.length > 0"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <PlantCard
                        v-for="plant in products.data"
                        :key="plant.id"
                        :plant="plant"
                    />

                    <Pagination :links="products.links" />
                </div>

                <div v-else class="text-center py-20 bg-gray-50 rounded-3xl">
                    <p class="text-xl text-gray-400">
                        В этой категории пока нет цветов...
                    </p>
                    <Link
                        href="/shop"
                        class="text-green-600 underline mt-4 inline-block"
                        >Вернуться ко всем товарам</Link
                    >
                </div>
            </main>
        </div>
    </div>
</template>
