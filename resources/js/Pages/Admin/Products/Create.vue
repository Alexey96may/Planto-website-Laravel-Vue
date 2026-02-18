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
                    <label class="block text-sm font-medium">Категория</label>
                    <select
                        v-model="form.category_id"
                        class="w-full border rounded-lg p-2"
                        required
                    >
                        <option value="" disabled>Выберите категорию</option>
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
