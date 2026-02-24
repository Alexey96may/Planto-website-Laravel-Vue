<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({
    categories: Array,
});

const form = useForm({
    title: "",
    description: "",
    price: "",
    category_id: "",
    image: "https://via.placeholder.com/300",
    is_trending: false,
    trending_order: 999,
});

const submit = () => {
    form.post(route("admin.products.store"));
};
</script>

<template>
    <AdminLayout>
        <Head title="Добавить товар" />

        <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow">
            <h1 class="text-2xl font-bold mb-6">Новое растение</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Название</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full border rounded-lg p-2"
                        required
                    />
                    <div v-if="form.errors.title" class="text-red-500 text-xs">
                        {{ form.errors.title }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Фото товара</label
                    >
                    <input
                        type="file"
                        @input="form.image = $event.target.files[0]"
                        class="w-full border rounded-lg p-2"
                        accept="image/*"
                    />
                    <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                        max="100"
                    >
                        {{ form.progress.percentage }}%
                    </progress>
                    <div v-if="form.errors.image" class="text-red-500 text-xs">
                        {{ form.errors.image }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Категория</label>
                    <select
                        v-model="form.category_id"
                        class="w-full border rounded-lg p-2"
                    >
                        <option :value="null">Без категории</option>
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
                        required
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

                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-100 mb-4"
                >
                    <div class="flex items-center space-x-3">
                        <input
                            id="is_trending"
                            v-model="form.is_trending"
                            type="checkbox"
                            class="w-5 h-5 text-green-600 rounded border-gray-300 focus:ring-green-500"
                        />
                        <label
                            for="is_trending"
                            class="text-sm font-medium text-gray-700 cursor-pointer"
                        >
                            Сразу в блок "Тренды"
                        </label>
                    </div>

                    <div v-if="form.is_trending" class="transition-all">
                        <label class="block text-sm font-medium text-gray-700"
                            >Порядок сортировки</label
                        >
                        <input
                            v-model="form.trending_order"
                            type="number"
                            class="mt-1 w-full border rounded-lg p-2 bg-white"
                            placeholder="0"
                        />
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button
                        type="button"
                        @click="window.history.back()"
                        class="px-4 py-2 text-gray-600"
                    >
                        Отмена
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg"
                    >
                        {{ form.processing ? "Сохранение..." : "Создать" }}
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
