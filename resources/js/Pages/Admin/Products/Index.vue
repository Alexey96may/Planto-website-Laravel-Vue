<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    products: Object,
});

const deleteProduct = (id) => {
    if (confirm("Вы уверены, что хотите удалить этот товар?")) {
        router.delete(route("admin.products.destroy", id));
    }
};

const updateTrending = (product, field, value) => {
    router.patch(
        route("admin.products.update-trending", product.id),
        {
            [field]: value,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                // Можно добавить уведомление "Сохранено"
            },
        },
    );
};
</script>

<template>
    <AdminLayout>
        <Head title="Управление товарами" />

        <div class="p-6 bg-white rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">
                    Товары ({{ products.total }})
                </h1>
                <Link
                    :href="route('admin.products.create')"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                >
                    + Добавить товар
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-4">Фото</th>
                            <th class="p-4">Название</th>
                            <th class="p-4 text-center">Тренд</th>
                            <th class="p-4 text-center">Порядок</th>

                            <th class="p-4">Категория</th>
                            <th class="p-4">Цена</th>
                            <th class="p-4 text-right">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="product in products.data"
                            :key="product.id"
                            class="border-b hover:bg-gray-50 transition"
                        >
                            <td class="p-4">
                                <img
                                    :src="product.image"
                                    class="w-12 h-12 rounded-lg object-cover"
                                />
                            </td>
                            <td class="p-4 font-medium">{{ product.title }}</td>

                            <td class="p-4 text-center">
                                <input
                                    type="checkbox"
                                    :checked="product.is_trending"
                                    @change="
                                        updateTrending(
                                            product,
                                            'is_trending',
                                            $event.target.checked,
                                        )
                                    "
                                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                />
                            </td>

                            <td class="p-4 text-center">
                                <input
                                    type="number"
                                    :value="product.trending_order"
                                    @blur="
                                        updateTrending(
                                            product,
                                            'trending_order',
                                            $event.target.value,
                                        )
                                    "
                                    class="w-16 p-1 border rounded text-center text-sm"
                                />
                            </td>

                            <td class="p-4">
                                <span
                                    class="px-2 py-1 bg-gray-100 rounded text-sm"
                                >
                                    {{
                                        product.category?.title ||
                                        "Без Категории"
                                    }}
                                </span>
                            </td>
                            <td class="p-4 font-bold">{{ product.price }} ₽</td>

                            <td class="p-4 text-right space-x-2">
                                <Link
                                    :href="
                                        route('admin.products.edit', product.id)
                                    "
                                    class="text-blue-600 hover:underline"
                                    >Изменить</Link
                                >
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    Удалить
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <Pagination :links="products.links" />
            </div>
        </div>
    </AdminLayout>
</template>
