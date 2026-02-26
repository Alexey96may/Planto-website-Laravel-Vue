<script setup>
import AppImage from "@/Components/AppImage.vue";
import { Link, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { ref } from "vue";

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

const removeFromCart = (productId) => {
    if (confirm("Вы уверены, что хотите удалить этот товар?")) {
        router.delete(route("cart.remove", productId), {
            preserveScroll: true,
            onSuccess: () => {
                // Здесь можно добавить уведомление об успехе
            },
        });
    }
};

const timers = ref({});

const updateQuantity = (item, newQuantity) => {
    if (newQuantity < 1) return; // Страховка

    if (newQuantity > item.stock) {
        alert("Извините, у нас нет столько растений 🌿");
        return;
    } // Страховка Number 2

    item.quantity = newQuantity;

    clearTimeout(timers.value[item.product_id]);

    timers.value[item.product_id] = setTimeout(() => {
        router.patch(
            route("cart.update", item.product_id),
            {
                quantity: newQuantity,
            },
            {
                preserveScroll: true,
            },
        );

        delete timers.value[item.product_id];
    }, 500);
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
                        :src="item.image_url"
                        class="w-16 h-16 object-cover mr-4"
                    />
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
                    Временно нет в наличии!
                </div>

                <div class="flex flex-col items-end justify-between">
                    <button
                        @click="removeFromCart(item.product_id)"
                        class="text-red-500 hover:text-red-700 text-sm mb-2"
                    >
                        Удалить
                    </button>
                    <div class="text-right font-bold" v-if="item.stock">
                        {{ item.total_price }} $
                    </div>
                </div>
            </div>

            <div class="mt-6 text-right text-xl font-bold">
                Итого: {{ cart.total_sum }} $
            </div>

            <Link
                href="/checkout"
                class="mt-4 block text-center bg-black text-white p-3 rounded"
                :disabled="cart.is_available"
            >
                Оформить заказ
            </Link>
        </div>

        <div v-else class="text-center py-10">
            <p>Корзина пуста</p>
            <Link :href="route('/')" class="text-blue-500"
                >Вернуться в магазин</Link
            >
        </div>
    </div>
</template>
