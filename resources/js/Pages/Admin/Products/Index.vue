<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, Link, router } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import Pagination from '@/Components/Pagination.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { PaginatedResponse, ProductWithCategory } from '@/types';
    import { formatUSD } from '@/utils/money';

    defineProps<{
        products: PaginatedResponse<ProductWithCategory>;
    }>();

    //todo
    const deleteProduct = (id: number): void => {
        if (confirm('Are you sure you want to delete this item?')) {
            router.delete(route('admin.products.destroy', id), {
                preserveScroll: true,
                onSuccess: () => {
                    // todo toast.success('Товар успешно удален');
                },
                onError: (errors) => {
                    // todo
                    console.error('Delete failed:', errors);

                    const errorMessage = Object.values(errors)[0] || 'Что-то пошло не так';
                    // todo toast.error(errorMessage);
                },
            });
        }
    };

    const isUpdating = ref(false);

    const updateProductField = (
        product: ProductWithCategory,
        field: keyof ProductWithCategory,
        value: string | number | boolean,
    ) => {
        let finalValue = value;
        isUpdating.value = true;

        if (['stock', 'trending_order'].includes(field)) {
            finalValue = value === '' ? 0 : Number(value);
        }

        if (product[field as keyof ProductWithCategory] === finalValue) return;

        const url = route('admin.products.update-trending', product.id);

        router.patch(
            route('admin.products.update-trending', product.id),
            { [field]: finalValue },
            {
                preserveScroll: true,
                onSuccess: () => {
                    // toDo Toast: "Сохранено!"
                    console.log(`${field} обновлен`);
                },
                onError: (errors) => {
                    const msg = Object.values(errors)[0] || 'Ошибка при обновлении';
                    alert(msg); // toDo oИли Toast
                },
                onFinish: () => (isUpdating.value = false),
            },
        );
    };

    const handleChange = (
        product: ProductWithCategory,
        field: keyof ProductWithCategory,
        event: Event,
    ) => {
        const target = event.target as HTMLInputElement;
        if (target) {
            updateProductField(product, field, target.checked);
        }
    };
</script>

<template>
    <AdminLayout>
        <Head title="Управление товарами" />

        <div class="p-6 bg-white rounded-xl shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Products ({{ products.total }})</h1>
                <Link
                    :href="route('admin.products.create')"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                >
                    + Add product
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="p-4">Photo</th>
                            <th class="p-4">Title</th>
                            <th class="p-4 text-center">Trend</th>
                            <th class="p-4 text-center">Order</th>
                            <th class="p-4">Category</th>
                            <th class="p-4">Price</th>
                            <th class="p-4 text-center">In Stock</th>
                            <th class="p-4 text-right">Actions</th>
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
                                    :src="product.image_url"
                                    class="w-12 h-12 rounded-lg object-cover"
                                />
                            </td>
                            <td class="p-4 font-medium">{{ product.title }}</td>

                            <td class="p-4 text-center">
                                <input
                                    type="checkbox"
                                    :checked="product.is_trending"
                                    :disabled="isUpdating"
                                    @change="handleChange(product, 'is_trending', $event)"
                                    class="w-4 h-4 text-green-600 rounded focus:ring-green-500"
                                />
                            </td>

                            <td class="p-4 text-center">
                                <input
                                    type="number"
                                    :value="product.trending_order"
                                    @blur="handleChange(product, 'trending_order', $event)"
                                    class="w-16 p-1 border rounded text-center text-sm"
                                />
                            </td>

                            <td class="p-4">
                                <span class="px-2 py-1 bg-gray-100 rounded text-sm">
                                    {{ product.category?.title || 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="p-4 font-bold">{{ formatUSD(product.price) }} ₽</td>

                            <td class="p-4 text-center">
                                <div class="flex flex-col items-center">
                                    <input
                                        type="number"
                                        min="0"
                                        :value="product.stock"
                                        @blur="handleChange(product, 'stock', $event)"
                                        :class="[
                                            'w-20 p-1 border rounded text-center text-sm transition-colors focus:ring-2 outline-none',
                                            product.stock <= 0
                                                ? 'border-red-500 bg-red-50 text-red-700'
                                                : 'border-gray-300',
                                            product.stock > 0 && product.stock <= 5
                                                ? 'border-orange-400 bg-orange-50'
                                                : '',
                                        ]"
                                    />
                                    <span
                                        v-if="product.stock <= 0"
                                        class="text-[10px] text-red-600 font-bold uppercase mt-1"
                                    >
                                        Empty
                                    </span>
                                    <span
                                        v-else-if="product.stock <= 5"
                                        class="text-[10px] text-orange-600 font-bold uppercase mt-1"
                                    >
                                        Few
                                    </span>
                                </div>
                            </td>

                            <td class="p-4 text-right space-x-2">
                                <Link
                                    :href="route('admin.products.edit', product.id)"
                                    :data="{ page: products.current_page }"
                                    class="text-blue-600 hover:underline"
                                    >Change</Link
                                >
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    Delete
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
