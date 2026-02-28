<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import { useForm } from '@inertiajs/vue3';

    import AdminLayout from '@/Layouts/AdminLayout.vue';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps({
        order: Object, // Здесь прилетят данные заказа вместе с вложенными items
    });

    const form = useForm({
        status: props.order.status,
    });

    const updateStatus = () => {
        form.patch(route('admin.orders.update', props.order.id), {
            preserveScroll: true,
            onSuccess: () => alert('Статус обновлен!'),
        });
    };
</script>

<template>
    <Head :title="'Заказ #' + order.id" />

    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Детали заказа #{{ order.id }}
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex items-center gap-2">
                <select v-model="form.status" class="border rounded p-2 text-sm">
                    <option value="new">Новый</option>
                    <option value="processing">В обработке</option>
                    <option value="completed">Завершен</option>
                    <option value="cancelled">Отменен</option>
                </select>
                <button
                    @click="updateStatus"
                    :disabled="form.processing"
                    class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700"
                >
                    Обновить
                </button>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Покупатель</h3>
                    <p class="mt-1 text-sm text-gray-600">Имя: {{ order.customer_name }}</p>
                    <p class="mt-1 text-sm text-gray-600">Телефон: {{ order.customer_phone }}</p>
                    <p class="mt-1 text-sm text-gray-600">
                        Адрес: {{ order.delivery_address || 'Не указан' }}
                    </p>
                </div>
                <div class="text-right">
                    <h3 class="text-lg font-medium text-gray-900">Статус</h3>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800"
                    >
                        {{ order.status }}
                    </span>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Товары в заказе</h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase">
                                Товар
                            </th>
                            <th class="text-center text-xs font-medium text-gray-500 uppercase">
                                Кол-во
                            </th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase">
                                Цена
                            </th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase">
                                Всего
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
                                {{ item.price }} ₽
                            </td>
                            <td class="py-4 text-sm font-bold text-gray-900 text-right">
                                {{ (item.price * item.quantity).toFixed(2) }}
                                ₽
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-6 text-right border-t pt-4">
                    <span class="text-xl font-bold">Итого: {{ order.total_price }} ₽</span>
                </div>
            </div>

            <Link
                :href="route('admin.orders.index')"
                class="inline-block text-sm text-gray-600 hover:underline"
            >
                ← Вернуться к списку
            </Link>
        </div>
    </div>
</template>
