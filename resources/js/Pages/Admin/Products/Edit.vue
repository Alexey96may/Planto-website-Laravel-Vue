<script setup>
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    product: Object,
    categories: Array,
});

// Заполняем форму старыми данными товара
const form = useForm({
    title: props.product.title,
    description: props.product.description,
    price: props.product.price,
    category_id: props.product.category_id,
    image: props.product.image,
});

const submit = () => {
    // В Laravel для обновления через Resource используется метод PUT или PATCH
    form.put(route("admin.products.update", props.product.id));
};
</script>

<template>
    <Head :title="'Редактировать ' + product.title" />

    <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow">
        <h1 class="text-2xl font-bold mb-6">Редактирование товара</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Название</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border rounded-lg p-2"
                />
            </div>

            <div>
                <label class="block text-sm font-medium">Категория</label>
                <select
                    v-model="form.category_id"
                    class="w-full border rounded-lg p-2"
                >
                    <option
                        v-for="cat in categories"
                        :key="cat.id"
                        :value="cat.id"
                    >
                        {{ cat.title }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">Цена</label>
                <input
                    v-model="form.price"
                    type="number"
                    class="w-full border rounded-lg p-2"
                />
            </div>

            <div>
                <label class="block text-sm font-medium">Описание</label>
                <textarea
                    v-model="form.description"
                    class="w-full border rounded-lg p-2"
                    rows="3"
                ></textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <button
                    type="button"
                    @click="window.history.back()"
                    class="text-gray-600"
                >
                    Отмена
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg"
                >
                    Сохранить изменения
                </button>
            </div>
        </form>
    </div>
</template>
