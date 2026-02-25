<script setup>
import { ref, watch, computed } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AppImage from "@/Components/AppImage.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

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
    product: Object,
    backUrl: String,
    cart_items: Object,
});

const count = ref(props.cart_items[props.product.id] || 1);

watch(
    () => props.cart_items[props.product.id],
    (newVal) => {
        if (newVal) count.value = newVal;
    },
);

const add = () => count.value++;
const remove = () => {
    if (count.value > 1) count.value--;
};

const addOrUpdateCart = () => {
    const id = props.product?.id;
    const newQuantity = count.value;

    if (newQuantity < 1 || !id) return;

    const isInCart = !!props.cart_items[id];

    if (isInCart) {
        router.patch(
            route("cart.update", id),
            {
                quantity: newQuantity,
            },
            {
                preserveScroll: true,
                onSuccess: () => {},
            },
        );
    } else {
        router.post(
            route("cart.add"),
            {
                product_id: id,
                quantity: newQuantity,
            },
            { preserveScroll: true, onSuccess: () => {} },
        );
    }
};

const removeFromCart = () => {
    const id = props.product?.id;

    if (!id) return;

    if (confirm("Вы уверены, что хотите удалить этот товар?")) {
        router.delete(route("cart.remove", id), {
            preserveScroll: true,
            onSuccess: () => {},
        });
    }
};

const buttonText = computed(() => {
    return props.cart_items[props.product.id]
        ? "Обновить количество за "
        : "Добавить в корзину за ";
});
</script>

<template>
    <div class="max-w-4xl mx-auto p-6">
        <Link :href="backUrl" class="text-sm text-gray-500 hover:text-green-600"
            >← К каталогу</Link
        >

        <div class="flex flex-col md:flex-row gap-8 mt-6">
            <div
                class="w-full md:w-1/2 bg-gray-100 aspect-square rounded-xl flex items-center justify-center text-gray-400"
            >
                <AppImage
                    :src="product?.image_url"
                    :alt="product.title"
                    className="rounded-lg shadow-md"
                />
            </div>

            <div class="w-full md:w-1/2">
                <h1 class="text-4xl font-bold text-gray-800">
                    {{ product.title }}
                </h1>

                <p v-if="product.category">
                    <Link
                        :href="
                            route('shop', {
                                category: product?.category?.slug,
                            })
                        "
                        class="text-sm text-gray-500 hover:text-green-600"
                        >{{ product?.category?.title }}</Link
                    >
                </p>
                <p v-else>Without Category</p>
                <p class="text-2xl text-green-600 font-semibold mt-2">
                    {{ product.price }} ₽
                </p>

                <div class="mt-6">
                    <h3 class="font-medium text-gray-700">Description:</h3>
                    <p class="text-gray-600 mt-2 leading-relaxed">
                        {{ product.description || "Description is loading..." }}
                    </p>
                </div>

                <div class="flex items-center gap-4 mt-8">
                    <div class="flex items-center border rounded-lg">
                        <button
                            @click="remove"
                            class="px-4 py-2 hover:bg-gray-100"
                        >
                            -
                        </button>
                        <span class="px-4 font-bold">{{ count }}</span>
                        <button
                            @click="add"
                            class="px-4 py-2 hover:bg-gray-100"
                        >
                            +
                        </button>
                    </div>

                    <button
                        class="bg-green-600 text-white px-8 py-2 rounded-lg hover:bg-green-700 transition"
                        @click="addOrUpdateCart"
                    >
                        {{ buttonText }} {{ product.price * count }} ₽
                    </button>
                    <button
                        class="bg-red-500 text-white px-8 py-2 rounded-lg hover:bg-red-700 transition"
                        v-if="cart_items[product.id]"
                        @click="removeFromCart"
                    >
                        Удалить из корзины
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
