<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Order, OrderStatus } from '@/types';

    const props = defineProps<{
        orders: Order[];
    }>();

    const getStatusLabel = (status: OrderStatus): string => {
        const labels: Record<OrderStatus, string> = {
            new: 'Accepted',
            processing: 'Processing',
            completed: 'Delivered',
            cancelled: 'Cancelled',
        };

        return labels[status];
    };
</script>

<template>
    <Head title="My orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My orders</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    v-if="orders.length === 0"
                    class="bg-white p-6 shadow rounded-lg text-center text-gray-500"
                >
                    You don't have any orders yet. Time to buy
                    <a :href="route('products.index')" class="text-indigo-600 underline"
                        >something</a
                    >!
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="order in orders"
                        :key="order.id"
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500"
                    >
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="text-sm text-gray-500"
                                    >Заказ от
                                    {{ new Date(order.created_at).toLocaleDateString() }}</span
                                >
                                <h3 class="text-lg font-bold">Заказ №{{ order.id }}</h3>
                            </div>
                            <div class="text-right">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold uppercase"
                                    :class="
                                        order.status === 'completed'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-blue-100 text-blue-800'
                                    "
                                >
                                    {{ getStatusLabel(order.status) }}
                                </span>
                                <p class="text-xl font-black mt-1">{{ order.total_price }} ₽</p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Состав заказа:</h4>
                            <ul class="text-sm text-gray-600">
                                <li v-for="item in order.items" :key="item.id">
                                    {{ item.product_name }} — {{ item.quantity }} шт.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
