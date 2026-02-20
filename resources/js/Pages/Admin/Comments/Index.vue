<script setup>
import { router } from "@inertiajs/vue3";

const props = defineProps({
    comments: Array,
});

const toggleStatus = (id) => {
    router.patch(route("admin.comments.update", id));
};

const deleteComment = (id) => {
    if (confirm("Точно удалить?")) {
        router.delete(route("admin.comments.destroy", id));
    }
};
</script>

<template>
    <div class="admin-panel">
        <h1>Модерация отзывов</h1>
        <table class="w-full mt-4 border">
            <thead>
                <tr class="bg-gray-100">
                    <th>Автор</th>
                    <th>Текст</th>
                    <th>Оценка</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="comment in comments"
                    :key="comment.id"
                    class="border-t"
                >
                    <td>{{ comment.user?.name || "Гость" }}</td>
                    <td class="max-w-xs truncate">{{ comment.body }}</td>
                    <td>{{ comment.rating }} ⭐</td>
                    <td>
                        <span
                            :class="
                                comment.is_active
                                    ? 'text-green-600'
                                    : 'text-red-600'
                            "
                        >
                            {{ comment.is_active ? "Активен" : "Скрыт" }}
                        </span>
                    </td>
                    <td class="space-x-2">
                        <button
                            @click="toggleStatus(comment.id)"
                            class="bg-blue-500 text-white px-2 py-1 rounded"
                        >
                            {{ comment.is_active ? "Скрыть" : "Одобрить" }}
                        </button>
                        <button
                            @click="deleteComment(comment.id)"
                            class="bg-red-500 text-white px-2 py-1 rounded"
                        >
                            Удалить
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
