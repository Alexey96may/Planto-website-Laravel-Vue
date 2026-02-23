<script setup>
import AppImage from "@/Components/AppImage.vue";
import { Link, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    cart: Object,
});

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

// Метод для удаления товара
const removeItem = (id) => {
    router.post("/cart/remove", { product_id: id });
};
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Ваша корзина</h1>

        <div v-if="cart.items.length > 0">
            <div
                v-for="item in cart.items"
                :key="item.product_id"
                class="flex justify-between border-b py-4"
            >
                <div class="flex items-center">
                    <img
                        :src="item.image"
                        class="w-16 h-16 object-cover mr-4"
                    />
                    <div>
                        <h3 class="font-semibold">{{ item.name }}</h3>
                        <p class="text-gray-500">
                            {{ item.price }} x {{ item.quantity }}
                        </p>
                    </div>
                </div>
                <div class="text-right font-bold">
                    {{ item.total_price }} руб.
                </div>
            </div>

            <div class="mt-6 text-right text-xl font-bold">
                Итого: {{ cart.total_sum }} руб.
            </div>

            <Link
                href="/checkout"
                class="mt-4 block text-center bg-black text-white p-3 rounded"
            >
                Оформить заказ
            </Link>
        </div>

        <div v-else class="text-center py-10">
            <p>Корзина пуста</p>
            <Link href="/" class="text-blue-500">Вернуться в магазин</Link>
        </div>
    </div>
</template>
