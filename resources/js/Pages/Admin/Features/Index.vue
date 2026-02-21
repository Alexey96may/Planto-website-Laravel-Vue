<script setup>
import { Link, router } from "@inertiajs/vue3";

// Получаем данные из контроллера
const props = defineProps({
    features: Array,
});

// Функция удаления
const deleteFeature = (id) => {
    if (confirm("Удалить эту карточку?")) {
        router.delete(route("admin.features.destroy", id));
    }
};
</script>

<template>
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Карточки слайдера</h1>
            <Link
                :href="route('admin.features.create')"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                + Добавить карточку
            </Link>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-left p-2">Фото</th>
                    <th class="text-left p-2">Заголовок</th>
                    <th class="text-left p-2">Порядок</th>
                    <th class="text-left p-2">Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="feature in features"
                    :key="feature.id"
                    class="border-b hover:bg-gray-50"
                >
                    <td class="p-2">
                        <img
                            v-if="feature.image_url"
                            :src="feature.image_url"
                            class="w-16 h-10 object-cover rounded"
                        />
                        <span v-else class="text-gray-400 text-xs"
                            >Нет фото</span
                        >
                    </td>
                    <td class="p-2">{{ feature.title }}</td>
                    <td class="p-2">{{ feature.order }}</td>
                    <td class="p-2">
                        <Link
                            :href="route('admin.features.edit', feature.id)"
                            class="text-blue-600 mr-3"
                            >Изм.</Link
                        >
                        <button
                            @click="deleteFeature(feature.id)"
                            class="text-red-600"
                        >
                            Удал.
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
