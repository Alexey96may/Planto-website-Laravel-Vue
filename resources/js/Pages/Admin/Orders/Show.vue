<script setup lang="ts">
    import { computed } from 'vue';

    import { Head, Link } from '@inertiajs/vue3';
    import { useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { FullOrder, OrderStatus } from '@/types';
    import { calculateTotal, formatUSD } from '@/utils/money';

    interface OrderUpdateForm {
        status: OrderStatus;
    }

    const props = defineProps<{
        order: FullOrder;
    }>();

    const form = useForm<OrderUpdateForm>({
        status: props.order.status as OrderStatus,
    });

    const updateStatus = (): void => {
        if (
            !confirm(
                'Are you sure you want to change the order status? The user will receive a notification.',
            )
        ) {
            return;
        }

        form.patch(route('admin.orders.update', props.order.id), {
            preserveScroll: true,
            onSuccess: () => alert('Status updated!'), //todo
            onError: (errors) => {
                console.error('Error updating status:', errors);

                if (errors.status) {
                    alert(`Error: ${errors.status}`);
                } else {
                    alert('An unexpected error occurred while saving');
                }
            },
        });
    };

    const orderTotal = computed(() => {
        return props.order.items.reduce((acc, item) => {
            return acc + calculateTotal(item.price, item.quantity);
        }, 0);
    });
</script>

<template>
    <AdminLayout>
        <Head :title="'Order #' + order.id" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order details #{{ order.id }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex items-center gap-2">
                    <select v-model="form.status" class="border rounded p-2 text-sm">
                        <option value="new">New</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Canceled</option>
                    </select>
                    <button
                        @click="updateStatus"
                        :disabled="form.processing"
                        class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700"
                    >
                        Refresh
                    </button>
                </div>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Buyer</h3>
                        <p class="mt-1 text-sm text-gray-600">Name: {{ order.customer_name }}</p>
                        <p class="mt-1 text-sm text-gray-600">Phone: {{ order.customer_phone }}</p>
                        <p class="mt-1 text-sm text-gray-600">
                            Address: {{ order.delivery_address || 'Not specified' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <h3 class="text-lg font-medium text-gray-900">Status</h3>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                        >
                            {{ order.status }}
                        </span>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Order items</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="text-left text-xs font-medium text-gray-500 uppercase">
                                    Product
                                </th>
                                <th class="text-center text-xs font-medium text-gray-500 uppercase">
                                    Quantity
                                </th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase">
                                    Price
                                </th>
                                <th class="text-right text-xs font-medium text-gray-500 uppercase">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="item in order.items" :key="item.id">
                                <td class="py-4 text-sm text-gray-900">
                                    {{ item.product_name }}
                                </td>
                                <td class="py-4 text-sm text-gray-900 text-center">
                                    {{ item.quantity }}
                                </td>
                                <td class="py-4 text-sm text-gray-900 text-right">
                                    {{ formatUSD(item.price) }}
                                </td>
                                <td class="py-4 text-sm font-bold text-gray-900 text-right">
                                    {{ formatUSD(calculateTotal(item.price, item.quantity)) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-6 text-right border-t pt-4">
                        <span class="text-xl font-bold">Total: {{ formatUSD(orderTotal) }}</span>
                    </div>
                </div>

                <Link
                    :href="route('admin.orders.index')"
                    class="inline-block text-sm text-gray-600 hover:underline"
                >
                    ← Back to list
                </Link>
            </div>
        </div></AdminLayout
    >
</template>
