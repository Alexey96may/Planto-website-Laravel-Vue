<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    title: "",
    description: "",
    link: "",
    order: 0,
    image: null, // Сюда попадет файл
});

const submit = () => {
    // Используем POST, так как отправляем файл
    form.post(route("admin.features.store"), {
        onSuccess: () => {
            // Если все ок, нас перекинет обратно (это настроено в контроллере)
        },
    });
};

// Функция для захвата файла из input
const handleFileUpload = (e) => {
    form.image = e.target.files[0];
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded">
        <h1 class="text-xl font-bold mb-4">Новая карточка</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block">Заголовок</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border p-2 rounded"
                    required
                />
                <div v-if="form.errors.title" class="text-red-500 text-sm">
                    {{ form.errors.title }}
                </div>
            </div>

            <div>
                <label class="block">Описание (абзацы)</label>
                <textarea
                    v-model="form.description"
                    class="w-full border p-2 rounded"
                    rows="4"
                ></textarea>
            </div>

            <div>
                <label class="block">Фотография</label>
                <input type="file" @change="handleFileUpload" class="w-full" />
                <div v-if="form.errors.image" class="text-red-500 text-sm">
                    {{ form.errors.image }}
                </div>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block">Ссылка (URL)</label>
                    <input
                        v-model="form.link"
                        type="text"
                        class="w-full border p-2 rounded"
                    />
                </div>
                <div class="w-1/2">
                    <label class="block">Порядок (цифра)</label>
                    <input
                        v-model="form.order"
                        type="number"
                        class="w-full border p-2 rounded"
                    />
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 disabled:bg-gray-400"
            >
                {{ form.processing ? "Загрузка..." : "Создать карточку" }}
            </button>
        </form>
    </div>
</template>
