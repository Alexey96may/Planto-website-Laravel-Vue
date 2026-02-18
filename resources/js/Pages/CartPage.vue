<script setup>
import AppImage from "@/Components/AppImage.vue";
import { Link, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

const props = defineProps({
    cartItems: Array,
    total: Number,
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
        <h1 class="text-3xl font-bold mb-8">Your Cart</h1>

        <div v-if="cartItems.length > 0">
            <div class="space-y-4 mb-10">
                <div
                    v-for="item in cartItems"
                    :key="item.id"
                    class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border"
                >
                    <div class="flex items-center gap-4">
                        <AppImage
                            :src="'/storage/' + item.image"
                            className="w-20 h-20 object-cover rounded-xl"
                        />
                        <div>
                            <h3 class="font-bold text-lg">{{ item.title }}</h3>
                            <p class="text-gray-500">{{ item.price }} ₽</p>
                        </div>
                    </div>

                    <button
                        @click="removeItem(item.id)"
                        class="text-red-500 hover:text-red-700 font-medium"
                    >
                        Удалить
                    </button>
                </div>
            </div>

            <div
                class="bg-gray-50 p-6 rounded-2xl border flex justify-between items-center"
            >
                <div>
                    <p class="text-gray-600">Итого к оплате:</p>
                    <p class="text-2xl font-black text-green-700">
                        {{ total }} ₽
                    </p>
                </div>
                <button
                    class="bg-black text-white px-8 py-3 rounded-xl font-bold hover:bg-gray-800 transition"
                >
                    Оформить заказ
                </button>
            </div>
        </div>

        <div v-else class="text-center py-20">
            <p class="text-xl text-gray-500 mb-6">В корзине пока пусто...</p>
            <Link href="/" class="text-green-600 font-bold underline"
                >Вернуться за покупками</Link
            >
        </div>
    </div>
</template>
